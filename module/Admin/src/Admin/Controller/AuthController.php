<?php

namespace Admin\Controller;

use Admin\Form\Login;
use Admin\Service\AuthService;
use Core\Controller\AbstractController;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * Authentification Controller
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class AuthController extends AbstractController {

    public function indexAction() {
        /** @var Login */
        $form = new Login();

        return new ViewModel(array('form' => $form));
    }

    public function loginAction() {
        /** @var Request */
        $req = $this->getRequest();

        if (!$req->isPost())
            throw new Exception("That page must be called with POST method.");

        /** @var Login */
        $form = new Login();

        $form->setData($req->getPost());

        $username = $form->get('username')->getValue();
        $password = $form->get('password')->getValue();

        /** @var AuthService */
        $authService = $this->getService('Admin\Service\AuthService');
        
        try {
            if ($authService->authentificate($username, $password))
                $this->redirect()->toUrl('/');
            return $this->getResponse();
        } catch (\Exception $e) {
            $this->getResponse()->setStatusCode(401);
            return new ViewModel(array('form' => $form, 'error' => $e->getMessage()));
        }
    }

    public function logoutAction() {
        $zAuthService = new AuthenticationService();

        if ($zAuthService->getIdentity() !== null) {
            $authService = $this->getService('Admin\ServiceAuthService');
            $authService->logout();
        }

        $this->redirect()->toUrl('/');
        return $this->getResponse();
    }

}

?>
