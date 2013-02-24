<?php

namespace AdminTest\Service;

use Admin\Model\Entity\User;
use Admin\Service\AuthService;
use Core\Test\ServiceTestCase;
use DateTime;
use Zend\Authentication\AuthenticationService;

/**
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @group Service
 */
class AuthServiceTest extends ServiceTestCase {

    /**
     * @return AuthService
     */
    public function getAuthService() {
        return $this->getService('Admin\Service\AuthService');
    }

    public function testAuthentication() {
        $auth = $this->getService('Admin\Service\AuthService');
        $user = $this->returnUser('admin');
        /* @var $auth AuthService */
        $auth->authentificate('admin', 'admin');

        $authS = new AuthenticationService;
        $identity = $authS->getIdentity();

        $this->assertNotNull($identity);
        $this->assertEquals($user, $identity);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid username or password.
     */
    public function testAuthenticationInvalidPassword() {
        $auth = $this->getService('Admin\Service\AuthService');
        $user = $this->returnUser('admin');
        /* @var $auth AuthService */
        $auth->authentificate('admin', 'error');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid username or password.
     */
    public function testAuthenticationUserNorExists() {
        $auth = $this->getService('Admin\Service\AuthService');
        /* @var $auth AuthService */
        $auth->authentificate('admin', 'admin');

        $this->getService('Core\Acl\Builder');
    }

    public function testAuthorizationNotAutheticatedGuest() {
        $auth = $this->getService('Admin\Service\AuthService');
        $this->getAlterConfig();

        /* @var $auth AuthService */
        $result = $auth->authorize('Application', 'Application\Controller\Index', 'index');
        $this->assertTrue($result);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage You was not allowed for use Admin\Controller\Index.index.
     */
    public function testAuthorizationNotAutheticatedGuestNotAllowedResource() {
        $auth = $this->getService('Admin\Service\AuthService');
        $this->getAlterConfig();

        /* @var $auth AuthService */
        $result = $auth->authorize('Admin', 'Admin\Controller\Index', 'index');
    }

    public function testAuthorizationAuthenficatedGuest() {
        $auth = $this->getService('Admin\Service\AuthService');
        $this->getAlterConfig();
        $user = $this->returnUser('guest', 'guest');

        /* @var $auth AuthService */
        $result = $auth->authentificate($user->getUsername(), 'guest');
        $this->assertTrue($result);

        $result = $auth->authorize('Application', 'Application\Controller\Index', 'index');
        $this->assertTrue($result);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage User guest was not allowed for use Admin\Controller\Index.index.
     */
    public function testAuthorizationAuthenficatedGuestNotAllowedResource() {
        $auth = $this->getService('Admin\Service\AuthService');
        $this->getAlterConfig();
        $user = $this->returnUser('guest', 'guest');

        /* @var $auth AuthService */
        $result = $auth->authentificate($user->getUsername(), 'guest');
        $this->assertTrue($result);

        $result = $auth->authorize('Admin', 'Admin\Controller\Index', 'index');
    }

    public function testAuthorizationAdminUser() {
        $auth = $this->getService('Admin\Service\AuthService');
        $this->getAlterConfig();
        $user = $this->returnUser('admin', 'admin');

        /* @var $result boolean */
        /* @var $auth AuthService */
        $result = $auth->authentificate($user->getUsername(), 'admin');
        $this->assertTrue($result);

        $result = $auth->authorize('Application', 'Application\Controller\Index', 'index');
        $this->assertTrue($result);

        $result = $auth->authorize('Admin', 'Admin\Controller\Index', 'index');
        $this->assertTrue($result);
    }

    public function testLogout() {
        $auth = $this->getService('Admin\Service\AuthService');
        $user = $this->returnUser('admin', 'admin');

        /* @var $auth AuthService */
        $auth->authentificate('admin', 'admin');

        $zauth = new \Zend\Authentication\AuthenticationService;
        $this->assertNotNull($zauth->getIdentity());
        $this->assertEquals($user, $zauth->getIdentity());

        $auth->logout();
        $this->assertNull($zauth->getIdentity());
    }

    public function returnUser($name, $role = 'admin') {
        $dao = $this->getService('Admin\Service\UserDAOService');

        $user = new User();
        $user->setData(array(
            'username' => $name,
            'password' => md5($name),
            'active' => 1,
            'email' => "$name@localhost.net",
            'dateCriation' => new DateTime,
            'name' => $name,
            'role' => $role,
        ));

        /* @var $auth UserDAOService */
        $dao->save($user);
        return $user;
    }

    public function getAlterConfig() {
        $config = $this->getService('Config');

        /* @var $config array */
        $config['acl'] =
                array(
                    'roles' => array(
                        'guest' => null,
                        'admin' => 'guest'
                    ),
                    'resources' => array(
                        'Application\Controller\Index.index',
                        'Admin\Controller\Index.index',
                    ),
                    'privilege' => array(
                        'guest' => array(
                            'allow' => array(
                                'Application\Controller\Index.index',
                            ),
                        ),
                        'admin' => array(
                            'allow' => array(
                                'Admin\Controller\Index.index',
                            ),
                        ),
                    ),
        );

        $this->getServiceManager()->setService('Config', $config);
    }

}

?>
