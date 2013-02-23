<?php

namespace Core\Acl;

use Core\Service\Service;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Rbac\Role;

/**
 * Class retrieves a filled ACL. 
 * @author Lucas dos Santos abreu <lucas.s.abreu@gmail.com>
 */
class Builder extends Service {

    protected $acl = null;

    /**
     * Constroi a ACL
     * @return Acl 
     */
    public function build() {
        if ($this->acl === null) {
            $config = $this->getServiceManager()->get('Config');
            $acl = new Acl();
            foreach ($config['acl']['roles'] as $role => $parent) {
                $acl->addRole(new Role($role), $parent);
            }
            foreach ($config['acl']['resources'] as $r) {
                $acl->addResource(new Resource($r));
            }
            foreach ($config['acl']['privilege'] as $role => $privilege) {
                if (isset($privilege['allow'])) {
                    foreach ($privilege['allow'] as $p) {
                        $acl->allow($role, $p);
                    }
                }
                if (isset($privilege['deny'])) {
                    foreach ($privilege['deny'] as $p) {
                        $acl->deny($role, $p);
                    }
                }
            }

            $this->acl = $acl;
        }
        return $this->acl;
    }

}