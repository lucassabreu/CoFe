<?php

namespace Core\Authentification\Doctrine;

use DoctrineModule\Authentication\Adapter\ObjectRepository;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Extension of <code>ObjectRepository</code> using <code>ValidatableAdapterInterface</code>.
 *
 * @see ObjectRepository
 * @see ValidatableAdapterInterface
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class DoctrineAdapter extends ObjectRepository implements ValidatableAdapterInterface {

    public function getCredential() {
        return $this->getCredentialValue();
    }

    public function getIdentity() {
        return $this->getIdentityValue();
    }

    public function setCredential($credential) {
        $this->setCredentialValue($credential);
        return $this;
    }

    public function setIdentity($identity) {
        $this->setIdentityValue($identity);
        return $this;
    }

}

?>
