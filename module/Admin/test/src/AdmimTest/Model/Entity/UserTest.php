<?php

namespace AdminTest\Model\Entity;

use Admin\Model\DAO\UserDAOInterface;
use Admin\Model\Entity\User;
use Core\Test\ModelTestCase;
use DateTime;
use Zend\InputFilter\InputFilter;

/**
 * Tests for Model Service UserDAO
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @group Entity
 */
class UserTest extends ModelTestCase {

    protected $daoName = 'Admin\Model\DAO\UserDAO';

    /**
     * Retrieve a filled user.
     * @return User
     */
    public function retrieveUser($name) {
        $user = new User();

        $user->setUsername($name);
        $user->setPassword(md5($name));
        $user->setName($name);
        $user->setRole('admin');
        $user->setEmail("$name@localhost.net");
        $user->setDateCriation(new \DateTime());
        $user->setActive(1);

        return $user;
    }

    /**
     * Retrieves the DAO.
     * @return UserDAOInterface
     */
    public function getDAO() {
        return $this->getService($this->daoName);
    }

    /**
     * Test implementation of InputFilter to attributes.
     */
    public function testInputFilterImplementation() {
        $user = new User();
        $this->assertInstanceOf('Zend\InputFilter\InputFilterAwareInterface', $user);

        /**
         * @var InputFilter
         */
        $inputFilter = $user->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $inputFilter);

        $data = $user->getInputFilter()->getRawValues();

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('username', $data);
        $this->assertArrayHasKey('password', $data);
        $this->assertArrayHasKey('active', $data);
        $this->assertArrayHasKey('role', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('dateCriation', $data);
    }

    /**
     * Test implmentation of filters to attributes.
     */
    public function testAttributesFilterRules() {
        $user = new User();

        $user->username = '   User<br>Name<br/>1 ';
        $this->assertEquals('username1', $user->username);

        $user->name = '   <b>Name</b> of User from Füssen';
        $this->assertEquals('Name of User from F&uuml;ssen', $user->name);

        $this->name = 'Name of User from F&uuml;ssen';
        $this->assertEquals('Name of User from F&uuml;ssen', $user->name);

        $user->email = '  user<br>name</br>@localhost.net';
        $this->assertEquals('username@localhost.net', $user->email);

        $user->role = '  adm<i>in</i>';
        $this->assertEquals('ADMIN', $user->role);

        $user->dateCriation = '2012-01-01';
        $this->assertEquals(new DateTime('2012-01-01'), $user->dateCriation);

        $user->active = 1;
        $this->assertEquals(true, $user->active);

        $user->active = 0;
        $this->assertEquals(false, $user->active);
    }

    /**
     * Test implementation of validation to attributes.
     */
    public function testAtributesValidationRules() {
        $user = new User();
        try {
            $user->username = 'Nequeporroquisquamestquidoloremipsumquiadolorsitametconsecteturadipiscivelit';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: username', $e->getMessage(), 'Valid username has correct length.');
        }

        $this->assertNull($user->username);

        try {
            $user->username = "_000user\'test%$\n";
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: username', $e->getMessage(), 'Valid username has correct characthers.');
        }

        $this->assertNull($user->username);

        try {
            $user->name = 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet consectetur adipisci velit';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: name', $e->getMessage(), 'Valid name has correct length.');
        }

        $this->assertNull($user->name);

        try {
            $user->email = 'Neque porro quisquam';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: email', $e->getMessage(), 'Valid email has e-mail format.');
        }
        $this->assertNull($user->email);

        try {
            $user->email = 'usern$%*ame@localhot';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: email', $e->getMessage(), 'Valid email has valid characters.');
        }

        $this->assertNull($user->email);

        try {
            $user->role = 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: role', $e->getMessage(), 'Valid role length.');
        }

        $this->assertNull($user->role);

        try {
            $user->role = 'Ad+-0)=';
        } catch (\Exception $e) {
            $this->assertInstanceOf('Core\Model\DAO\Exception\DAOException', $e);
            $this->assertStringStartsWith('Input inválido: role', $e->getMessage(), 'Valid role characters.');
        }

        $this->assertNull($user->role);
    }

}

?>
