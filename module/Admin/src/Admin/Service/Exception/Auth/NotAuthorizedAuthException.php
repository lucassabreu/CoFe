<?php

namespace Admin\Service\Exception\Auth;

use Admin\Model\Entity\User;
use Admin\Service\Exception\AuthException;
use InvalidArgumentException;

/**
 * User can't access some resource.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class NotAuthorizedAuthException extends AuthException {

    /**
     * Controller requested
     * @var string
     */
    protected $controller;

    /**
     * Action requested
     * @var string
     */
    protected $action;

    /**
     * Constructor of class
     * 
     * @param User $user
     * @param string $controller Controller requested
     * @param string $action Action of controller
     * @param Exception|mixed $previous Exception has been caused this one
     * @param string $message custom message
     * @param string $code custom code error
     */
    public function __construct($user, $controller, $action, $previous = null, $message = null, $code = null) {
        if ($user !== null && !($user instanceof User))
            throw new InvalidArgumentException("First param 'user' must be a instance of \Admin\Model\Entity\User.");
        
        if ($message === null) {
            if ($user === null)
                $message = "You was not allowed for use $controller.$action.";
            else
                $message = "User $user->username was not allowed for use $controller.$action.";
        }

        parent::__construct($user, $message, $code, $previous);

        $this->action = $action;
        $this->controller = $controller;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

}

?>
