<?php

namespace Core\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service base class
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
abstract class Service implements ServiceLocatorAwareInterface {

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator = null;

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieves a service by the name.
     * @param string $name Name of requested service.
     * @return ServiceLocatorAwareInterface|mixed
     */
    public function getService($name) {
        return $this->getServiceLocator()->get($name);
    }

}
?>
