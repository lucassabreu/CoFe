<?php

namespace Admin\Controller;

use Admin\Form\User\User as UserForm;
use Core\Controller\AbstractController;
use Exception;
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
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $form = new UserForm();
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
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $request = $this->getRequest();
            /* @var $request \Zend\Http\Request */
            $form = new UserForm();

            if ($request->isPost()) {
                $form->setData($request->getPost());
                $form->isValid();
                /* echo '<pre>';
                  if ($form->isValid()) {
                  echo 'bateu aqui!<br/>';
                  } else {
                  print_r($request->getPost());
                  var_dump($form->getMessages());
                  echo 'bateu aqui no outro!<br/>';
                  }
                  echo '</pre>'; */
            } else {
                $form->setData($user->getData());
            }

            return new ViewModel(array('form' => $form));
        }
    }

    public function lockAction() {
        $id = $this->params('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');
            try {
                $this->dao()->lock($user);
                return $this->redirect()->toRoute('user');
            } catch (Exception $e) {
                return new ViewModel(array('form' => $form));
            }
        }
    }

    public function unlockAction() {
        $id = $this->params('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /** @var $user \Admin\Model\Entity\User */
            if ($user == null)
                return $this->redirect()->toRoute('user');
            try {
                $this->dao()->unlock($user);
                return $this->redirect()->toRoute('user');
            } catch (Exception $e) {
                return $this->forward()->dispatch('user', array('action' => 'detail', 'id' => $id, 'exception' => $e));
            }
        }
    }

    public function removeAction() {
        return null;
    }

}

?>
