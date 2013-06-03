<?php

namespace Application\Controller;

use Core\Controller\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * CRUD of Category
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryController extends AbstractController {
    public function __construct() {
        $this->daoName = 'Application\Service\CategoryDAOService';
    }
    
    public function indexAction(){
        return new ViewModel();
    }
}

?>
