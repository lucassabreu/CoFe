<?php

namespace Admin\Controller;

use Admin\Form\User\Form as UserForm;
use Admin\Form\User\Remove as RemoveForm;
use Admin\Model\Entity\User;
use Core\Controller\AbstractController;
use Core\Model\DAO\Exception\DAOException;
use Exception;
use Zend\Http\Request;
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

    /**
     * List the users (paginate)
     * @return ViewModel
     */
    public function indexAction() {
        $page = $this->params()->fromRoute('page', 1);

        $adapter = $this->dao()->getAdapterPaginator(null);

        $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel(array('users' => $paginator));
    }

    /**
     * Shows details of specific user
     * @return ViewModel
     */
    public function detailAction() {

        $id = $this->params()->fromRoute('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null) {
                return $this->redirect()->toRoute('user');
            }
            $form = new UserForm();
            $form->setData($user->getData());

            return new ViewModel(array('form' => $form));
        }
    }

    public function createAction() {
        return null;
    }

    /**
     * Update detail data of user
     * @return ViewModel
     */
    public function updateAction() {
        $id = $this->params()->fromRoute('id');
        $valid = true;

        if ($id == null) {
            $id = $this->getRequest()->getPost('id');
            $valid = false;
        }

        $exception = $this->params('exception');

        $request = $this->getRequest();
        /* @var $request Request */

        $returnTo = $request->getPost('returnTo');

        if (is_null($returnTo))
            $returnTo = $this->url()->fromRoute('userList');

        if (!is_null($exception))
            $valid = false;

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $request = $this->getRequest();
            /* @var $request Request */
            $form = new UserForm();

            if ($request->isPost() && $valid) {
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    try {
                        $user->setData($form->getData());
                        $user = $this->dao()->save($user);
                        $form->setData($user->getData());
                    } catch (Exception $e) {
                        if ($e instanceof DAOException)
                            $form->addExceptionMessage($e);
                        else
                            $form->addExceptionMessage('Occurred internal errors');
                    }
                } else {
                    $emailMessages = $form->get('email')->getMessages();

                    if (!is_null($emailMessages) && count($emailMessages) > 0)
                        $form->get('email')->setMessages(array('email' => 'The input is not a valid email address'));
                }
            } else {
                $form->setData($user->getData());
            }

            if (!is_null($exception))
                $form->addExceptionMessage($exception);

            $form->get('cancel')->setAttribute('formaction', $returnTo);

            return new ViewModel(array('form' => $form));
        }
    }

    public function lockAction() {
        $id = $this->params()->fromRoute('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        $request = $this->getRequest();
        /* @var $request Request */

        $returnTo = $request->getPost('returnTo');

        if (is_null($returnTo))
            $returnTo = $this->url()->fromRoute('userList');

        if ($id == null)
            return $this->redirect()->toUrl($returnTo);
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null)
                return $this->redirect()->toRoute('userList');
            try {
                $this->dao()->lock($user);
                return $this->redirect()->toUrl($returnTo);
            } catch (Exception $e) {
                return $this->forward()->dispatch('user', array('action' => 'update', 'id' => $id, 'exception' => $e));
            }
        }
    }

    public function unlockAction() {
        $id = $this->params()->fromRoute('id');

        if ($id == null)
            $id = $this->getRequest()->getPost('id');

        $request = $this->getRequest();
        /* @var $request Request */

        $returnTo = $request->getPost('returnTo');

        if (is_null($returnTo))
            $returnTo = $this->url()->fromRoute('userList');

        if ($id == null)
            return $this->redirect()->toUrl($returnTo);
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null)
                return $this->redirect()->toRoute('userList');
            try {
                $this->dao()->unlock($user);
                return $this->redirect()->toUrl($returnTo);
            } catch (Exception $e) {
                return $this->forward()->dispatch('user', array('action' => 'detail', 'id' => $id, 'exception' => $e));
            }
        }
    }

    public function removeAction() {

        $id = $this->params()->fromRoute('id');

        $request = $this->getRequest();
        /* @var $request Request */

        if ($id == null)
            $id = $request->getPost('id');

        $returnTo = $request->getPost('returnTo');

        if (is_null($returnTo))
            $returnTo = $this->url()->fromRoute('userList');

        if ($id == null)
            return $this->redirect()->toUrl($returnTo);
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null)
                return $this->redirect()->toRoute('userList');

            $submitRemove = null;
            if ($request->isPost())
                $submitRemove = $request->getPost('submitRemove', null);

            $form = new RemoveForm();

            if ($submitRemove === null) {
                $form->setData($user->getData());
                $form->get('submitCancel')->setAttribute('formaction', $returnTo);

                return array(
                    'form' => $form,
                );
            } else {
                $form->setData($request->getPost());

                try {
                    $this->dao()->remove($user);
                    return $this->redirect()->toRoute('userList');
                } catch (Exception $e) {
                    return $this->forward()->dispatch('user', array('action' => 'detail', 'id' => $id, 'exception' => $e));
                }
            }
        }
    }

}

?>
