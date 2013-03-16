<?php

namespace Admin\Service\Exception\Auth;

use Admin\Model\Entity\User;
use Admin\Service\Exception\AuthException;
use InvalidArgumentException;

/**
 * Lanched when try to authentificate with a user not active.
 *
 * @author Lucas dos Santos Abreu <lucas.a.abreu@gmail.com>
 */
class InactiveUserAuthException extends AuthException {

    public function __construct(User $user, $message = null, $code = null, $previous = null) {
        if (is_null($user)) {
            throw new InvalidArgumentException("User parameter can't be null.");
        }

        if ($message === null)
            $message = 'The user ' . $user->getName() . ' isn\'t active.';

        parent::__construct($user, $message, $code, $previous);
    }

}

?>
