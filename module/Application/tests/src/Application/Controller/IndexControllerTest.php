<?php

namespace Application\Controller;

use Core\Test\ControllerTestCase;

class IndexControllerTest extends ControllerTestCase {

    protected $controllerFQDN = 'Application\Controller\IndexController';
    protected $controllerRoute = 'application';

    public function testIndexActionCanBeAccessed() {
        $this->routeMatch->setParam('action', 'action_nao_existente');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

}