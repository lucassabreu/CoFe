<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        //$this->getServiceLocator()->
        //$this->getServiceLocator()->get(__CLASS__); //->get(__CLASS__);

        $this->varargs("teste", "novo", 1, 2, 3, 1, 4, 5, 6, 5);

        return new ViewModel();
    }

    public function varargs($args) {
        foreach (func_get_args() as $arg) {
            echo $arg;
        }
    }

}
