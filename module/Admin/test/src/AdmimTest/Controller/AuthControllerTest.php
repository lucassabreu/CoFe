<?php

namespace AdminTest\Controller;

use Admin\Model\Entity\User;
use Core\Test\ControllerTestCase;
use DateTime;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;

/**
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @group Controller
 */
class AuthControllerTest extends ControllerTestCase {

    protected $controllerName = 'Admin\Controller\AuthController';
    protected $controllerRoute = 'admin';

    /**
     * Valid if the controller will response with 404.
     */
    public function test404() {
        $this->routeMatch->setParam('action', 'action_nao_existente');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Valid return of index action
     */
    public function testIndexPage() {
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $variables = $result->getVariables();
        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('form', $variables);
        $this->assertInstanceOf('Admin\Form\Login', $variables['form']);
    }

    /**
     * @expectedException Exception
     */
    public function testLoginInvalidMethod() {
        $this->routeMatch->setParam('action', 'login');
        $result = $this->controller->dispatch($this->request);
    }

    public function testLoginWithoutData() {
        $this->routeMatch->setParam('action', 'login');
        $this->request->setMethod(Request::METHOD_POST);
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $vars = $result->getVariables();

        $this->assertCount(2, $vars);
        $this->assertArrayHasKey('form', $vars);
        $this->assertArrayHasKey('error', $vars);

        $this->assertEquals('You\'re trying login in with a invalid user or password.', $vars['error']);

        $form = $vars['form'];
        $this->assertInstanceOf('Admin\Form\Login', $form);
        $this->assertEquals(null, $form->get('username')->getValue());
        $this->assertEquals(null, $form->get('password')->getValue());
    }

    public function testInvalidLoginData() {

        $dao = $this->getService('Admin\Service\UserDAOService');
        $user = new User();

        $user->setUsername('user');
        $user->setPassword(md5('user'));

        $user->setName('user');
        $user->setDateCriation(new DateTime);
        $user->setEmail('user@localhost.net');
        $user->setRole('guest');
        $user->setActive(true);

        $dao->save($user);

        $this->routeMatch->setParam('action', 'login');

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->getPost()->set('username', 'user');
        $this->request->getPost()->set('password', '');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $vars = $result->getVariables();

        $this->assertCount(2, $vars);
        $this->assertArrayHasKey('form', $vars);
        $this->assertArrayHasKey('error', $vars);

        $this->assertEquals('You\'re trying login in with a invalid user or password.', $vars['error']);

        $form = $vars['form'];
        $this->assertInstanceOf('Admin\Form\Login', $form);
        $this->assertEquals('user', $form->get('username')->getValue());
        $this->assertEquals('', $form->get('password')->getValue());
    }

    public function testValidLoginData() {
        $user = $this->addUser();

        $this->routeMatch->setParam('action', 'login');

        $this->request->setMethod(Request::METHOD_POST);
        $this->request->getPost()->set('username', 'user');
        $this->request->getPost()->set('password', 'user');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $zas = new AuthenticationService();
        $this->assertNotNull($zas->getIdentity());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Location: /', $response->getHeaders()->get('Location')->toString());
    }

    /**
     * @depends testValidLoginData
     */
    public function testLogout() {
        $user = $this->addUser('user');

        $as = $this->getService('Admin\Service\AuthService');
        $as->authentificate($user->getUsername(), 'user');

        $zas = new AuthenticationService();
        $this->assertNotNull($zas->getIdentity());

        $this->routeMatch->setParam('action', 'logout');
        $result = $this->controller->dispatch($this->request);
        $response2 = $this->controller->getResponse();

        $this->assertNull($zas->getIdentity());

        $this->assertEquals(302, $response2->getStatusCode());
        $this->assertEquals('Location: /', $response2->getHeaders()->get('Location')->toString());
    }

    protected function addUser($username = 'user') {
        $dao = $this->getService('Admin\Service\UserDAOService');
        $user = new User();

        $user->setUsername($username);
        $user->setPassword(md5($username));

        $user->setName($username);
        $user->setDateCriation(new DateTime);
        $user->setEmail("$username@localhost.net");
        $user->setRole('guest');
        $user->setActive(true);

        $dao->save($user);

        return $user;
    }

}

?>
