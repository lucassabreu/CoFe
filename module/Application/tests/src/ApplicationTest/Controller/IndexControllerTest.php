<?php

namespace ApplicationTest\Controller;

use Core\Test\ControllerTestCase;

class IndexControllerTest extends ControllerTestCase {

    public function __construct() {
        $this->controllerRoute = 'application';
        $this->controllerName = 'Application\Controller\IndexController';
    }

    public function test404() {
        $this->routeMatch->setParam('action', 'action_nao_existente');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

}