<?php

namespace AdminTest\Controller;

use Admin\Model\Entity\User;
use Admin\Service\UserDAOService;
use Core\Test\ControllerTestCase;
use DateTime;
use Zend\Form\Form;
use Zend\Http\Request;
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
        $this->assertArrayHasKey('form', $variables);

        $form = $variables['form'];
        /* @var $form Form */
        $this->assertInstanceOf('Admin\Form\User', $form);

        $this->assertEquals(1, $form->getData()->id);
    }

    public function testUpdateInvalidUser() {
        $result = $this->dispath('update', array('route' => array('id' => 1)));
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */

        $this->assertEquals(302, $responce->getStatusCode());
        $this->assertEquals('/admin/index', $responce->getHeaders()->get('Location'));
    }

    public function testUpdateFormValidUser() {
        $user = $this->returnUser('user');

        $result = $this->dispath('update', array('route' => array('id' => 1)));
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $variables = $result->getVariables();
        $this->assertArrayHasKey('form', $variables);

        $form = $variables['form'];
        /* @var $form Form */
        $this->assertInstanceOf('Admin\Form\User', $form);

        $this->assertEquals(1, $form->getData()->id);
    }

    public function testUpdateUserValues() {
        $user = $this->returnUser('user');

        $params = array(
            'post' => array(
                'id' => 1,
                'name' => 'User02',
                'username' => 'user',
                'password'
            ),
        );

        $result = $this->dispath('update', $params, Request::METHOD_POST);
        /* @var $result ViewModel */
        $responce = $this->controller->getResponse();
        /* @var $responce Response */

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $variables = $result->getVariables();
        $this->assertArrayHasKey('form', $variables);

        $form = $variables['form'];
        /* @var $form Form */
        $this->assertInstanceOf('Admin\Form\User', $form);

        $this->assertEquals(1, $form->getData()->id);
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
