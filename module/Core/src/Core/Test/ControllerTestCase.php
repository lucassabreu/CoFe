<?php
namespace Core\Test;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

abstract class ControllerTestCase extends TestCase
{
    /**
     * The ActionController we are testing
     *
     * @var AbstractActionController
     */
    protected $controller;

    /**
     * A request object
     *
     * @var Request
     */
    protected $request;

    /**
     * A response object
     *
     * @var Response
     */
    protected $response;

    /**
     * The matched route for the controller
     *
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * An MVC event to be assigned to the controller
     *
     * @var MvcEvent
     */
    protected $event;

    /**
     * The Controller fully qualified domain name, so each ControllerTestCase can create an instance
     * of the tested controller
     *
     * @var string
     */
    protected $controllerFQDN;

    /**
     * The route to the controller, as defined in the configuration files
     *
     * @var string
     */
    protected $controllerRoute;

    public function setup()
    {
        parent::setup();
        
        $routes = $this->getRoutes();
        
        $this->controller = new $this->controllerFQDN; //$this->serviceManager->get($this->controllerFQDN);
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array(
            'router' => array(
                'routes' => array(
                    $this->controllerRoute => $routes[$this->controllerRoute]
                )
            )
        ));
        $this->getEvent()->setRouteMatch($this->routeMatch);
        
        $this->controller->setEvent($this->getEvent());
        $this->controller->setServiceLocator($this->getServiceManager());
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->controller);
        unset($this->request);
        unset($this->routeMatch);
    }
}