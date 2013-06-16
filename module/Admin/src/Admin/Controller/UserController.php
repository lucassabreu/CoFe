<?php

namespace Admin\Controller;

use Admin\Form\User;
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

        $id = $this->params('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $form = new User();
            $form->setData($user->getData());

            return new ViewModel(array('form' => $form));
        }
    }

    public function createAction() {
        return null;
    }

    public function updateAction() {
        $id = $this->params('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $form = new User();
            $form->setData($user->getData());

            return new ViewModel(array('form' => $form));
        }
    }

    public function removeAction() {
        return null;
    }

}

?>
