<?php

namespace Admin\Service\Exception\Auth;

use Admin\Service\Exception\AuthException;

/**
 * Exception related with a invalid information for authentification
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class InvalidUserAuthException extends AuthException {

    public function __construct(\Admin\Model\Entity\User $user = null, $message = null, $code = null, $previous = null) {
        if ($message == null) {
            $message = 'You\'re trying login in with a invalid user or password.';
        }
        
        parent::__construct(null, $message, $code, $previous);
    }

}

?>
