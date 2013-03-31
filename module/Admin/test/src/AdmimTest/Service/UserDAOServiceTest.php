<?php

namespace AdminTest\Service;

use Admin\Model\Entity\User;
use Admin\Service\UserDAOService;
use AdminTest\Service\EntityTest;
use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Test\ServiceTestCase;
use DateTime;

/**
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @group DAOService
 */
class UserDAOServiceTest extends ServiceTestCase {

    /**
     * 
     * @return UserDAOService
     */
    protected function getDAOService() {
        return $this->getService('Admin\Service\UserDAOService');
    }

    public function testSave() {
        $dao = $this->getDAOService();

        $ent = new EntityTest();
        try {
            $dao->save($ent);
        } catch (DAOException $e) {
            $this->assertEquals("The service " . get_class($dao) . " not manage the class " . get_class($ent), $e->getMessage());
        }

        $user = $this->newUser('user01');

        $user = $dao->save($user);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('user01', $user->getUsername());

        $user02 = $this->newUser('user02');
        $user02->setUsername('user01');

        try {
            $user02 = $dao->save($user02);
        } catch (DAOException $e) {
            $this->assertEquals("Already exists a user with username: $user->username.", $e->getMessage());
        }

        $user->setRole('common');
        $dao->save($user);
    }

    public function testFindById() {
        $user = array();
        $user[] = $this->newUser('user01');
        $user[] = $this->newUser('user02');
        $user[] = $this->newUser('user03');

        $dao = $this->getDAOService();

        $dao->save($user[0]);
        $userO = $dao->save($user[1]);
        $dao->save($user[2]);

        $daoi = $dao->getDAOInterface();

        $userF = $dao->findById($userO->getId());

        $this->assertEquals($userO->getName(), $userF->getName());
        $this->assertEquals($userO->getId(), $userF->getId());
    }

    public function testFindByUsername() {
        $dao = $this->getDAOService();

        $user1 = $this->newUser('test');
        $user1->setName('Another thing');
        $dao->save($user1);

        $user2 = $this->newUser('test2');
        $user2->setName('One');
        $dao->save($user2);

        $userL = $dao->findByUsername('test');

        $this->assertEquals($user1->getId(), $userL->getId());
        $this->assertEquals($user1->getUsername(), $userL->getUsername());
        $this->assertEquals($user1->getName(), $userL->getName());
    }

    public function testChangePassword() {
        $user = $this->newUser('test');

        $dao = $this->getDAOService();

        try {
            $dao->changePassword($user, 'test', 'test2');
            $this->assertEquals(true, false, "Should throw a exception before here.");
        } catch (DAOException $e) {
            $this->assertEquals("To use " . get_class($dao) . "::changePassword the user must be previewsly saved.", $e->getMessage());
        }

        $dao->save($user);

        try {
            $dao->changePassword($user, 'test', 'test2');
            $this->assertEquals(true, false, "Should throw a exception before here.");
        } catch (DAOException $e) {
            $this->assertEquals("The new password not matches with confirm password.", $e->getMessage());
        }

        $user = $this->newUser('test');
        $user->setId(2);
        try {
            $dao->changePassword($user, 'test', 'test');
            $this->assertEquals(true, false, "Should throw a exception before here.");
        } catch (DAOException $e) {
            $this->assertEquals("User test not exists.", $e->getMessage());
        }

        $user = $this->newUser('test');
        $user->setId(1);

        $values = $user->getInputFilter()->getRawValues();
        unset($values['id']);
        unset($values['password']);

        foreach ($values as $key => $value) {

            if ($key == 'dateCriation') {
                $user->$key = new DateTime('now');
                $user->$key->modify('+7 day');
            } else {
                if ($key === 'email') {
                    $user->$key = 'm@localhost.net';
                } else {
                    if ($key === 'active') {
                        $user->$key = 0;
                    } else
                        $user->$key .= "K";
                }
            }

            try {
                $dao->changePassword($user, 'test', 'test');
                $this->assertEquals(true, false, "Should throw a exception before here.");
            } catch (DAOException $e) {
                $this->assertEquals("Method " . get_class($dao) . "::changePassword is only for update the password, other changes must use: " .
                        get_class($dao) . "::save.", $e->getMessage());
            }

            $user->$key = $value;
        }

        $user = $this->newUser('test');
        $user->setId(1);

        try {
            $dao->changePassword($user, 'test2', 'test');
            $this->assertEquals(true, false, "Should throw a exception before here.");
        } catch (DAOException $e) {
            $this->assertEquals("Informed password not matches with old password.", $e->getMessage());
        }

        $user->setPassword(md5('nova_senha'));

        $user = $dao->changePassword($user, 'test', 'nova_senha');

        $this->assertEquals(md5('nova_senha'), $user->getPassword());
    }

    public function testFetchAll() {
        $dao = $this->getDAOService();

        $dao->save($this->newUser('user01'));
        $dao->save($this->newUser('user02'));
        $dao->save($this->newUser('user03'));
        $dao->save($this->newUser('user04'));
        $dao->save($this->newUser('user05'));
        $dao->save($this->newUser('user06'));
        $dao->save($this->newUser('user07'));
        $dao->save($this->newUser('user08'));
        $dao->save($this->newUser('user09'));

        $users = $dao->fetchAll();

        $this->assertCount(9, $users);

        $this->assertEquals('user01', $users[0]->getUsername());
        $this->assertEquals('user02', $users[1]->getUsername());
        $this->assertEquals('user03', $users[2]->getUsername());
        $this->assertEquals('user04', $users[3]->getUsername());
        $this->assertEquals('user05', $users[4]->getUsername());
        $this->assertEquals('user06', $users[5]->getUsername());
        $this->assertEquals('user07', $users[6]->getUsername());
        $this->assertEquals('user08', $users[7]->getUsername());
        $this->assertEquals('user09', $users[8]->getUsername());

        $users = $dao->fetchAll(5);
        $this->assertCount(5, $users);
        $this->assertEquals('user01', $users[0]->getUsername());
        $this->assertEquals('user02', $users[1]->getUsername());
        $this->assertEquals('user03', $users[2]->getUsername());
        $this->assertEquals('user04', $users[3]->getUsername());
        $this->assertEquals('user05', $users[4]->getUsername());

        $users = $dao->fetchAll(5, 3);
        $this->assertCount(5, $users);
        $this->assertEquals('user04', $users[0]->getUsername());
        $this->assertEquals('user05', $users[1]->getUsername());
        $this->assertEquals('user06', $users[2]->getUsername());
    }

    public function testFetchByParam() {
        $dao = $this->getDAOService();

        $dao->save($this->newUser('user01'));
        $dao->save($this->newUser('user02'));
        $dao->save($this->newUser('user03'));
        $dao->save($this->newUser('user04'));
        $dao->save($this->newUser('user05'));
        $dao->save($this->newUser('user06'));
        $dao->save($this->newUser('user07'));
        $dao->save($this->newUser('user08'));
        $dao->save($this->newUser('user09'));

        /* @var $user User[] */
        $users = $dao->fetchByParams(array('username' => 'user01'));
        $this->assertCount(1, $users);
        $this->assertEquals('user01', $users[0]->getUsername());

        $users = $dao->fetchByParams(array('id' => array(3, 5)));
        $this->assertCount(3, $users);
        $this->assertEquals('user03', $users[0]->getUsername());
        $this->assertEquals('user04', $users[1]->getUsername());
        $this->assertEquals('user05', $users[2]->getUsername());

        $users = $dao->fetchByParams(array('username' => 'user%'), 2);
        $this->assertCount(2, $users);
        $this->assertEquals('user01', $users[0]->getUsername());
        $this->assertEquals('user02', $users[1]->getUsername());

        $users = $dao->fetchByParams(array('username' => array('user02', 'user07')), 2, 2);
        $this->assertCount(2, $users);
        $this->assertEquals('user04', $users[0]->getUsername());
        $this->assertEquals('user05', $users[1]->getUsername());
    }

    /**
     * Makes a basic User;
     * @param string $name
     * @return User
     */
    protected function newUser($name) {
        $user = new User();
        $user->setUsername($name);
        $user->setPassword(md5($name));
        $user->setName($name);
        $user->setActive(1);
        $user->setRole('admin');
        $user->setEmail("$name@localhost.net");

        return $user;
    }

}