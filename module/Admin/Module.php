<?php

namespace Admin;

/**
 * Bootstrap module class
 * @ignore
 */
class Module {

    /**
     * @return array
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Realize the boot of module
     * 
     * @param MvcEvent $e
     */
    public function onBootstrap($e) {
        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        /** @var \Zend\EventManager\SharedEventManager $sharedEvents */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();

        //adiciona eventos ao módulo
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
    }

    /**
     * Autorize the access at system resources
     * @param  MvcEvent $event Event
     * @return boolean
     */
    public function mvcPreDispatch($event) {
        $di = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        $moduleName = $routeMatch->getParam('module');
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');

        $authService = $di->get('Admin\Service\AuthService');
        
        try {
            $authService->authorize($moduleName, $controllerName, $actionName);
        } catch(\Exception $e) {
            
        }    
        
        return true;
    }

}
