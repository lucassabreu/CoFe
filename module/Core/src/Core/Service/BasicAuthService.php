<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BasicAuthService
 *
 * @author Lucas
 */
abstract class BasicAuthService {

    /**
     * Adapter usado para a autenticação
     * @var Adapter
     */
    private $dbAdapter;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct($dbAdapter = null) {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Faz a autenticação dos usuários
     * 
     * @param array $params
     * @return array
     */
    public function authenticate($username, $password) {
        $password = md5($password);
        $auth = new AuthenticationService();

        $authAdapter = new AuthAdapter($this->dbAdapter);
        $authAdapter
                ->setTableName('user')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setIdentity($username)
                ->setCredential($password);

        $authAdapter->setIdentity($username)
                ->setCredential($password);

        $result = $auth->authenticate($authAdapter);
        if (!$result->isValid()) {
            throw new Exception("Login or password invalid.");
        }

        //salva o user na sessão
        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $authAdapter->getResultRowObject());

        return true;
    }

    /**
     * Faz o logout do sistema
     *
     * @return void
     */
    public function logout() {
        $auth = new AuthenticationService();
        $session = $this->getServiceManager()->get('Session');
        $session->offsetUnset('user');
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
        $auth = new AuthenticationService();
        $role = 'guest';
        if ($auth->hasIdentity()) {
            $session = $this->getServiceManager()->get('Session');
            $user = $session->offsetGet('user');
            $role = $user->role;
        }

        $resource = $controllerName . '.' . $actionName;
        $acl = $this->getServiceManager()->get('Core\Acl\Builder')->build();
        if ($acl->isAllowed($role, $resource)) {
            return true;
        }
        throw new \Exception("User $user->username has no authorize for use $controllerName.$actionName.");
    }

}

?>
