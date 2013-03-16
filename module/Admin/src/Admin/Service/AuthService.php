<?php

namespace Admin\Service;

use Admin\Model\Entity\User;
use Closure;
use Core\Service\Service;
use Exception;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class for authentification processes.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class AuthService extends Service {

    /**
     * @var ValidatableAdapterInterface
     */
    protected $adapter = null;

    /**
     * Constructor of class
     * @param ValidatableAdapterInterface $adapter Adapter used on validation
     * @param Closure $returnUser Function will return the user for put on session
     */
    public function __construct(ValidatableAdapterInterface $adapter) {
        $this->setAdapter($adapter);
    }

    /**
     * Realize the authentification based on params.
     * @param string $username
     * @param string $password
     * @return boolean
     * @throws Exception If authentification fails
     */
    public function authentificate($username, $password) {
        $this->getAdapter()
                ->setIdentity($username)
                ->setCredential(md5($password));

        $auth = new AuthenticationService;

        $result = $auth->authenticate($this->getAdapter());

        if ($result->isValid()) {
            /**
             * @var $user User
             */
            $user = $auth->getIdentity();
            return true;
        } else
            throw new Exception\Auth\InvalidUserAuthException();
    }

    /**
     * Remove identity from session
     * @return void
     */
    public function logout() {
        $auth = new AuthenticationService;
        $auth->clearIdentity();
        return true;
    }

    /**
     * Authorize the logged user for resource
     * @param string $moduleName module name
     * @param string $controllerName controller name
     * @param string $actionName action name
     * @return boolean
     */
    public function authorize($moduleName, $controllerName, $actionName) {
        $auth = new AuthenticationService;
        $role = 'guest';
        $user = null;
        if ($auth->hasIdentity()) {
            /**
             * @var $user User
             */
            $user = $auth->getIdentity();
            $role = strtolower($user->getRole());
        }

        $resource = $controllerName . '.' . $actionName;
        $acl = $this->getService('Core\Acl\Builder')->build();
        if ($acl->isAllowed($role, $resource)) {
            return true;
        }
        throw new Exception(is_null($user) ? "You was not allowed for use $controllerName.$actionName." : "User $user->username was not allowed for use $controllerName.$actionName.");
    }

    public function getAdapter() {
        return $this->adapter;
    }

    public function setAdapter(ValidatableAdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }

}

?>
