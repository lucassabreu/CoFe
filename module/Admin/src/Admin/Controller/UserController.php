<?php

namespace Admin\Controller;

use Admin\Form\User\Form;
use Admin\Form\User\Form as UserForm;
use Admin\Form\User\Remove as RemoveForm;
use Admin\Form\User\ResetPassword;
use Admin\Model\Entity\User;
use Admin\Service\UserDAOService;
use Core\Controller\AbstractController;
use Core\Model\DAO\Exception\DAOException;
use Core\Service\Util\MailUtilService;
use DateTime;
use Exception;
use Zend\Authentication\AuthenticationService;
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
     * Shows details of in session user
     * @return ViewModel
     */
    public function detailProfileAction() {
        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        $form = new UserForm();
        $form->setData($sessionUser->getData());

        return array('form' => $form);
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

    /**
     * Create a new User
     * @return null
     */
    public function createAction() {
        $request = $this->getRequest();
        /* @var $request Request */

        $form = new UserForm(true);

        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');

            if ($submitAction === 'create') {
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    try {
                        $user = new User();
                        $data = $form->getData();

                        unset($data['id']);
                        unset($data['active']);

                        $data['dateCreation'] = new DateTime('now');

                        $user->setData($data);
                        $this->dao()->save($user);

                        return $this->redirect()->toRoute('user', array('action' => 'detail', 'id' => $user->getId()));
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
            }
        }

        $form->get('submitAction')->setValue('create');
        $form->get('dateCreation')->setValue(new DateTime('now'));

        $form->get('cancel')->setAttribute('formaction', $this->url()->fromRoute('userList'));

        return array('form' => $form);
    }

    /**
     * Update detail data of session user
     * @return ViewModel
     */
    public function updateProfileAction() {
        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        $userUpdate = $this->dao()->findById($sessionUser->getId());

        $request = $this->getRequest();
        /* @var $request Request */

        $valid = false;
        if ($request->isPost()) {
            $submitAction = $request->getPost('submitAction');
            if ($submitAction === 'updateProfile')
                $valid = true;
        }

        $form = $this->updateUser($userUpdate, $this->url()->fromRoute('user', array('action' => 'detailProfile')), $valid);

        $authService->getStorage()->write($userUpdate);

        if ($form === null)
            return $this->redirect()->toRoute('user', array('action' => 'detailProfile'));

        $form->get('submitAction')->setValue('updateProfile');

        return array('form' => $form);
    }

    /**
     * Update detail data of user
     * @return ViewModel
     */
    public function updateAction() {
        $id = $this->params()->fromRoute('id');
        $valid = true;

        $request = $this->getRequest();
        /* @var $request Request */

        if ($id == null) {
            $id = $request->getPost('id');
            $valid = false;
        }

        $returnTo = $request->getPost('returnTo');

        if (is_null($returnTo))
            $returnTo = $this->url()->fromRoute('userList');

        if ($id == null)
            return $this->redirect()->toRoute('user');
        else {
            $user = $this->dao()->findById($id);
            /* @var $user User */
            if ($user == null)
                return $this->redirect()->toRoute('user');

            $form = $this->updateUser($user, $returnTo, $valid);

            if ($form === null)
                return $this->redirect()->toRoute('user', array('action' => 'detail', 'id' => $user->getId()));

            return new ViewModel(array('form' => $form));
        }
    }

    /**
     * Realize the of user at database or retrieve a filled form on error.
     * @param User $user User to update
     * @param string $returnTo Uri to return on Cancel button
     * @param boolean $valid Can update on base
     * @return Form|null Form filled with user and error / null if update occurs
     */
    protected function updateUser(User $user, $returnTo, $valid) {

        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        $exception = $this->params()->fromRoute('exception');
        /* @var $exception \Exception */

        if (!is_null($exception))
            $valid = false;

        $request = $this->getRequest();
        /* @var $request Request */

        $form = new UserForm();

        if ($request->isPost() && $valid) {
            $postData = $request->getPost();
            $postData['active'] = $user->isActive();

            if ($sessionUser->getRole() !== 'admin')
                $postData['role'] = $user->getRole();

            $form->setData($postData);

            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    unset($data['active']);
                    unset($data['dateCreation']);
                    unset($data['password']);

                    echo $data['id'];

                    if ($sessionUser->getRole() !== 'admin')
                        unset($data['role']);

                    $user->setData($data);
                    $user = $this->dao()->save($user);
                    $form->setData($user->getData());

                    return null;
                } catch (Exception $e) {
                    if ($e instanceof DAOException)
                        $form->addExceptionMessage($e);
                    else {
                        $form->addExceptionMessage('Occurred internal errors');
                        throw $e;
                    }
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

        return $form;
    }

    /**
     * Lock a User password by param id (route or POST)
     * @return ViewModel
     */
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

    /**
     * Unlock a user passed by param id (route or POST)
     * @return ViewModel
     */
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

    public function changePasswordAction() {
        return null;
    }

    /**
     * Change Password 
     * @return array
     */
    public function resetPasswordAction() {

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
                return $this->redirect()->toRoute('user');

            $form = new ResetPassword();
            $form->setData($user->getData());

            if ($request->isPost()) {
                if ($request->getPost('resetPassword') === 'resetPassword') {
                    $dao = $this->dao();
                    /* @var $dao UserDAOService */

                    $password = $dao->generatePassword();
                    $dao->resetPasswordTo($user, $password);

                    $mailService = $this->getService('Core\Service\Util\\MailUtilService');
                    /* @var $mailService MailUtilService */

                    $mailService->sendEmail("Reset Password", "Teste: $password", \Zend\Mime\Mime::TYPE_TEXT, array($user->getEmail() => $user->getName()));
                }
            }

            $form->get('cancel')->setAttribute('formaction', $returnTo);

            return array(
                'form' => $form,
            );
        }
    }

    /**
     * Remove a user
     */
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
