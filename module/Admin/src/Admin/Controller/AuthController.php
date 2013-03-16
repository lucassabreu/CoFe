<?php

namespace Admin\Controller;

use Admin\Form\Login;
use Core\Controller\AbstractController;

/**
 * Authentification Controller
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class AuthController extends AbstractController {
    public function indexAction() {
        /**
         * @var Login
         */
        $form = new Login();
        
        return new ViewModel(array('form' => $form));
    }
}

?>
