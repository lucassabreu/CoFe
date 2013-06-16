<?php

namespace Admin\Service;

use Admin\Model\DAO\UserDAOInterface;
use Admin\Model\Entity\User;
use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Service\AbstractDAOService;

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
        /**
         * @var User
         */
        $user = null;

        if ($ent instanceof User)
            $user = $ent;
        else
            throw new DAOException("The service " . __CLASS__ . " not manage the class " . get_class($ent));

        $userOld = $this->findByUsername($user->getUsername());

        if ($userOld != null && $userOld->getId() != $user->getId())
            throw new DAOException("Already exists a user with username: $user->username.");

        if ($user->getId() === null) {
            return parent::save($user);
            // regra de criação
        } else {
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

        if ($user->getPassword() <> md5($newPassword))
            throw new DAOException("The new password not matches with confirm password.");

        $userOld = $this->findById($user->getId());

        if ($userOld === null || !($userOld instanceof User))
            throw new DAOException("User $user->name not exists.");

        // this method is oly for update the password, another values must use method save.
        if ($user->getUsername() != $userOld->getUsername()
                || $user->getRole() != $userOld->getRole()
                || $user->getName() != $userOld->getName()
                || $user->getEmail() != $userOld->getEmail()
                || $user->getDateCreation() != $userOld->getDateCreation()
                || $user->getActive() != $userOld->getActive())
            throw new DAOException("Method " . __METHOD__ . " is only for update the password, other changes must use: " . __CLASS__ . "::save.");

        if ($userOld->getPassword() != md5($oldPassword))
            throw new DAOException("Informed password not matches with old password.");

        $userOld->setPassword($user->getPassword());

        $this->dao->changePassword($userOld, $oldPassword, $newPassword);

        return $user;
    }

}

?>
