<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Core\Controller;

use Core\Model\DAO\DAOInterface;
use Zend\Authentication\AuthenticationService;
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
     * DAO instance for controller
     * 
     * @var DAOInterface
     */
    private $dao = null;

    /**
     * Class name of default DAO
     * @var string
     */
    protected $daoName = null;

    /**
     * Retrieves the of requested service by name
     * @param string $name
     * @return mixed|ServiceManagerAwareInterface|ServiceLocatorAwareInterface
     */
    public function getService($name) {
        return $this->getServiceLocator()->get($name);
    }

    /**
     * Retrieves a DAO instance
     * @return DAOInterface
     */
    public function dao($name = null) {

        if ($name === null) {
            if ($this->dao === null)
                $this->dao = $this->getService($this->daoName);

            return $this->dao;
        }
        else
            return $this->getService($name);
    }

    public function indexAction() {
        return new ViewModel();
    }

    /**
     * Returns information of session user, or null if has no user logged
     * @param string (optional) $attribute Attribute of session wanted
     * @return mixed|null
     */
    public function getSessionUser($attribute = null) {

        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();

        if ($sessionUser === null)
            return null;

        if ($attribute === null)
            return $sessionUser;
        else
            return $sessionUser->{$attribute};
    }

    public function redirectBack($route, $param = array()) {
        echo "<pre>";
        $request = $this->getRequest();
        /* @var $request Request */
        $return = $request->getHeader('Referer');

        if ($return === null) {
            return $this->redirect()->toRoute($route, $param);
        } else {
            return $this->redirect()->toUrl($return);
        }

        echo "</pre>";
    }

}

?>