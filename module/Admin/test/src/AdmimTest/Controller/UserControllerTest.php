<?php

namespace AdminTest\Controller;

use Admin\Model\Entity\User;
use Admin\Service\UserDAOService;
use Core\Test\ControllerTestCase;
use DateTime;
use Zend\Http\Response;
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
        $this->assertCount(10, $usersPaginator);

        foreach ($usersPaginator as $key => $user)
            $this->assertEquals($users[$key]->getUsername(), $user->getUsername());
    }

    public function returnUser($name, $role = 'admin', $active = true) {
        $dao = $this->getService('Admin\Service\UserDAOService');

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

        /* @var $dao UserDAOService */
        $dao->save($user);
        return $user;
    }

}

?>
