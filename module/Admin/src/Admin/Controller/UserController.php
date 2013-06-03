<?php

namespace Admin\Controller;

use Core\Controller\AbstractController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * CRUD of User
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class UserController extends AbstractController {

    public function __construct() {
        $this->daoName = 'Admin\Service\UserDAOService';
    }

    public function indexAction() {
        $page = $this->params()->fromRoute('page', 1);

        $adapter = $this->dao()->getAdapterPaginator(null);

        $paginator = new Paginator($adapter);
        $paginator->setPageRange(20);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel(array('users' => $paginator));
    }

    public function detailAction() {
        return null;
    }

    public function detailCreate() {
        return null;
    }

    public function detailUpdate() {
        return null;
    }

    public function detailRemove() {
        return null;
    }

}

?>
