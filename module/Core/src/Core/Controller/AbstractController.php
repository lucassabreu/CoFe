<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\View\Model\ViewModel;

/**
 * Base class for controller at application
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
abstract class AbstractController extends AbstractActionController {

    /**
     * Retrieves the of requested service by name
     * @param string $name
     * @return mixed|ServiceManagerAwareInterface|ServiceLocatorAwareInterface
     */
    public function getService($name) {
        return $this->getServiceLocator()->get($name);
    }

    public function indexAction() {
        return new ViewModel();
    }

}

?>