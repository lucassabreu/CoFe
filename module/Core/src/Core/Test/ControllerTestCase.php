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
    protected $controllerName;

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
        
        $this->controller = new $this->controllerName; //$this->serviceManager->get($this->controllerName);
        $this->request    = new Request();
        $this->routeMatch = $this->getEvent()->getRouteMatch();
        
        $this->routeMatch = new RouteMatch(array(
            'router' => array(
                'routes' => array(
                    $this->controllerRoute => $routes[$this->controllerRoute]
                )
            )
        ));
        $this->event = $this->getEvent(); 
        $this->event->setRouteMatch($this->routeMatch);
        
        $this->controller->setEvent($this->event);
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