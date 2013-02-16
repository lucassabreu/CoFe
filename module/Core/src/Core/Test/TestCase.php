<?php

namespace Core\Test;

use Zend\Json\Server\Smd\Service;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var Application
     */
    protected $application;

    /**
     * Returns the current bootstrap instance.
     * @return Bootstrap
     */
    public function getBootstrap() {
        return Bootstrap::getInstance();
    }

    public function setup() {
        parent::setup();
        $this->application = $this->getBootstrap()->getApplication();

        //$this->createDatabase();
    }

    public function tearDown() {
        parent::tearDown();
        $this->application = null;
        $this->getBootstrap()->reset();
        //$this->dropDatabase();
    }

    public function getApplication() {
        return $this->application;
    }

    /**
     * Retrieve the current ServiceManager.
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->getBootstrap()->getServiceManager();
    }

    /**
     * Retrieve the current MvcEvent.
     * @return MvcEvent
     */
    public function getEvent() {
        return $this->getBootstrap()->getEvent();
    }

    /**
     * Retrieve the list of routes.
     * @return array
     */
    public function getRoutes() {
        return $this->getBootstrap()->getRoutes();
    }

    /**
     * Retrieve Service
     *
     * @param  string $service
     * @return Service
     */
    protected function getService($service) {
        return $this->getServiceManager()->get($service);
    }

}