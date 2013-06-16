<?php

namespace Admin\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * Helper for access data of user in session
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class AuthHelper extends AbstractHelper {

    public function __invoke($attribute = null) {
        $authService = new AuthenticationService();

        if ($attribute == null)
            return $authService->getIdentity();
        
        return $authService->getIdentity()->{$attribute};
    }

}

?>
