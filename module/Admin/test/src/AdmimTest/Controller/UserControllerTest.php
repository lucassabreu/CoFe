<?php

namespace AdminTest\Controller;

use Admin\Model\Entity\User;
use Admin\Service\UserDAOService;
use Core\Test\ControllerTestCase;
use DateTime;
use Zend\Http\Response;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @group Controller
 * @group UserController
 */
class UserControllerTest extends ControllerTestCase {

    public function __construct() {
        $this->controllerName = 'Admin\Controller\UserController';
        $this->controllerRoute = 'admin';
    }

    public function test404() {
        $result = $this->dispath('action_nao_existente');
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testIndexWithoutPage() {
        $users = array();
        for ($i = 0; $i < 10; $i++) {
            $users[] = $this->returnUser("user$i");
        }

        /** @var $result ViewModel */
        $result = $this->dispath('index');
        /** @var $responce Response */
        $responce = $this->controller->getResponse();

        $this->assertEquals(200, $responce->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $variables = $result->getVariables();
        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('users', $variables);

        $usersPaginator = $variables['users'];

        $this->assertInstanceOf('Zend\Paginator\Paginator', $usersPaginator);
        /* @var $usersPaginator Paginator */
        $this->assertCount(10, $usersPaginator->getCurrentItems());

        foreach ($usersPaginator as $key => $user)
            $this->assertEquals($users[$key]->getUsername(), $user->getUsername());
    }

    public function testIndexWithPage() {
        $users = array();
        for ($i = 0; $i < 30; $i++)
            $users[] = $this->returnUser("user$i");

        $result = $this->dispath('index', array('route' => array('page' => 2)));
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */

        $this->assertEquals(200, $responce->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $variables = $result->getVariables();
        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('users', $variables);

        $usersPaginator = $variables['users'];
        /* @var $usersPaginator Paginator */

        $this->assertInstanceOf('Zend\Paginator\Paginator', $usersPaginator);
        $this->assertCount(10, $usersPaginator->getCurrentItems());

        foreach ($usersPaginator as $key => $user)
            $this->assertEquals($users[$key + 10]->getUsername(), $user->getUsername());
    }

    public function testDetailInvalidUser() {
        $result = $this->dispath('detail', array('route' => array('id' => 0)));
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */
        
        $this->assertEquals(302, $responce->getStatusCode());
        $this->assertEquals('/admin/index', $responce->getHeaders()->get('Location'));
    }
    
    
    public function testDetailUser() {
        $user = $this->returnUser('user');
        
        $result = $this->dispath('detail', array('route' => array('id' => 1)));
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        
        $variables = $result->getVariables();
        $this->assertArrayHasKey('user', $variables);
        
        $userResult = $variables['user'];
        $this->assertInstanceOf('Admin\Model\Entity\User', $userResult);
        
        $this->assertEquals(1, $user->getId());
    }

    public function returnUser($name, $role = 'admin', $active = true) {
        $dao = $this->getService('Admin\Service\UserDAOService');
        /* @var $dao UserDAOService */

        $user = new User();
        $user->setData(array(
            'username' => $name,
            'password' => md5($name),
            'active' => $active,
            'email' => "$name@localhost.net",
            'dateCriation' => new DateTime,
            'name' => $name,
            'role' => $role,
        ));

        $dao->save($user);
        return $user;
    }

}

?>
