<?php

namespace Application\Controller;

use Application\Form\Category\CategoryList;
use Core\Controller\AbstractController;

/**
 * CRUD of entity Category
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryController extends AbstractController {

    public function __construct() {
        $this->daoName = 'Application\Service\CategoryDAOService';
    }

    /**
     * List categories with pagination
     */
    public function indexAction() {

        $form = new CategoryList();

        $categories = $this->dao()->fetchAllTop($this->getSessionUser());

        return array(
            'form' => $form,
            'categories' => $categories,
        );
    }

    /**
     * View for create a new Category
     */
    public function createAction() {
        
    }

}

?>
