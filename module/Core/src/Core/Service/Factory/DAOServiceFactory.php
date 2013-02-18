<?php

namespace Core\Service\Factory;

/**
 * Factory of DAO Service classes.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
final class DAOServiceFactory implements \Zend\ServiceManager\AbstractFactoryInterface {

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    protected function getConfig() {
        $gConfig = $this->serviceLocator->get('Configuration');

        if (isset($gConfig['service_manager']) && isset($gConfig['service_manager']['dao_services']))
            return $gConfig['service_manager']['dao_services'];
        else
            return array();
    }

    public function canCreateServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $this->serviceLocator = $serviceLocator;
        $config = $this->getConfig();
        return isset($config[$requestedName]);
    }

    public function createServiceWithName(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return null;
    }

}

?>
