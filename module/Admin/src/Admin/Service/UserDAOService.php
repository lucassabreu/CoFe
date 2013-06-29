<?php

namespace Admin\Service;

use Admin\Model\DAO\UserDAOInterface;
use Admin\Model\Entity\User;
use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Service\AbstractDAOService;
use Zend\Authentication\AuthenticationService;

/**
 * Service used by manage user entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class UserDAOService extends AbstractDAOService implements UserDAOInterface {

    /**
     * @var UserDAOInterface
     */
    protected $dao;

    public function save(Entity $ent) {

        $user = null;

        if ($ent instanceof User)
            $user = $ent;
        else
            throw new DAOException("The service " . __CLASS__ . " not manage the class " . get_class($ent));

        /* @var $user User */

        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        if ($sessionUser->getRole() !== 'admin') {
            if ($sessionUser->getId() !== $user->getId()) {
                throw new DAOException("You have no permission to edit values of that user.");
            }
        }

        $userOld = $this->findByUsername($user->getUsername());
        /* @var $userOld User */

        if ($sessionUser->getRole() !== 'admin' && $userOld !== null) {
            if ($sessionUser->getUsername() !== $userOld->getUsername()) {
                throw new DAOException("You have no permission to edit values of that user.");
            }
        }

        if ($userOld !== null && $userOld->getId() !== $user->getId())
            throw new DAOException("Already exists a user with username: $user->username.");

        if ($user->getId() === null) {
            $user->setPassword(md5($user->getEmail()));
            $user->setActive(false);

            $user->validate();
            return parent::save($user);
            // regra de criação
        } else {
            $old = $this->findById($user->getId());
            /* @var $old User */
            if ($old->getPassword() !== $user->getPassword()) {
                throw new DAOException("To change password you must use changePassword method.");
            }

            if ($old->isActive() != $user->isActive()) {
                throw new DAOException("To change active you must use lock or unlock method.");
            }

            if ($sessionUser->getRole() !== 'admin') {
                if ($user->getRole() != $userOld->getRole() // 
                        || $user->getDateCreation() != $userOld->getDateCreation() // 
                        || $user->isActive() != $userOld->isActive())
                    throw new DAOException("You have no permission to edit values role, date creation or active of your user.");
            }

            /*$old->setData($user->getData());

            $old = parent::save($old);

            $user->setData($old->getData());

            return $user;*/
            
            return parent::save($user);
        }
    }

    public function findById() {
        $id = func_get_args();

        if (count($id) != 1)
            throw new DAOException('User key is not composed, uses: ' . __METHOD__ . '($id).');

        return parent::findById($id[0]);
    }

    public function findByUsername($username) {
        return $this->dao->findByUsername($username);
    }

    public function changePassword(User $user, $oldPassword, $newPassword) {
        if ($user->id === null)
            throw new DAOException("To use " . __METHOD__ . " the user must be previewsly saved.");

        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        if ($sessionUser->getRole() !== 'admin') {
            if ($sessionUser->getId() !== $user->getId()) {
                throw new DAOException("You have no permission to edit values of that user.");
            }
        }

        if ($user->getPassword() <> md5($newPassword))
            throw new DAOException("The new password not matches with confirm password.");

        $userOld = $this->findById($user->getId());

        if ($userOld === null || !($userOld instanceof User))
            throw new DAOException("User $user->name not exists.");

        // this method is oly for update the password, another values must use method save.
        if ($user->getUsername() != $userOld->getUsername()                // 
                || $user->getRole() != $userOld->getRole() // 
                || $user->getName() != $userOld->getName() // 
                || $user->getEmail() != $userOld->getEmail() // 
                || $user->getDateCreation() != $userOld->getDateCreation() // 
                || $user->isActive() != $userOld->isActive())
            throw new DAOException("Method " . __METHOD__ . " is only for update the password, other changes must use: " . __CLASS__ . "::save.");

        if ($userOld->getPassword() != md5($oldPassword))
            throw new DAOException("Informed password not matches with old password.");

        $userOld->setPassword($user->getPassword());

        $user = $this->dao->changePassword($userOld, $oldPassword, $newPassword);

        return $user;
    }

    public function lock(User $user) {
        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        if ($sessionUser->getRole() !== 'admin') {
            throw new DAOException("You have no permission to edit active status of your user.");
        }

        $old = null;
        /* @var $old User */
        if ($user != null && $user->getId() != null) {
            $old = $this->findById($user->getId());

            if ($old === null)
                throw new DAOException("The user must exists to use this method, use save method first.");

            if ($old->isActive()) {
                $old->setActive(false);
                $old = $this->dao->lock($old);

                $user->setData($old->getData());
                return $user;
            }
        }

        return $user;
    }

    public function unlock(User $user) {
        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        if ($sessionUser->getRole() !== 'admin') {
            throw new DAOException("You have no permission to edit active status of your user.");
        }

        $old = null;
        /* @var $old User */
        if ($user != null && $user->getId() != null) {
            $old = $this->findById($user->getId());

            if ($old === null)
                throw new DAOException("The user must exists to use this method, use save method first.");

            if (!$old->isActive()) {
                $old->setActive(true);
                $old = $this->dao->unlock($old);

                $user->setData($old->getData());
                return $user;
            }
        }

        return $user;
    }

    public function remove(Entity $ent) {
        $authService = new AuthenticationService();
        /* @var $authService AuthenticationService */

        $sessionUser = $authService->getIdentity();
        /* @var $sessionUser User */

        if ($sessionUser->getRole() !== 'admin') {
            throw new DAOException("You have no permission to remove your user.");
        }

        return parent::remove($ent);
    }

}

?>
