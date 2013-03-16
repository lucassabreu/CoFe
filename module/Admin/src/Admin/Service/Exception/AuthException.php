<?php

namespace Admin\Service\Exception;

use Admin\Model\Entity\User;
use Exception;

/**
 * Base Exception class for invalid access.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
abstract class AuthException extends Exception {

    /**
     * User related with the exception
     * @var User
     */
    protected $user = null;

    /**
     * Build a generic AuthException
     * @param string $message Message of exception
     * @param string $code Code Error of exception
     * @param Exception|mixed $previous Previus Throwable lanched causing this exception
     * @param User $user User related with the exception
     */
    public function __construct(User $user = null, $message = null, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->user = $user;
    }

    /**
     * Retrieves the user related.
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

}

?>
