<?php

namespace Admin\Model\DAO;

use Admin\Model\Entity\User;
use Core\Model\DAO\DAOInterface;

/**
 * Interface contract for DAO objects for class User
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see \Admin\Model\User
 */
interface UserDAOInterface extends DAOInterface {

    /**
     * Retrieves, if exists, the User the username from param
     * @param string $username
     * @return User
     * @deprecated since version 1.0
     */
    public function findByUsername($username);

    /**
     * Alter the password of User
     * @param User $user
     * @param string $oldPassword
     * @param string $newPassword
     * @return User
     */
    public function changePassword(User $user, $oldPassword, $newPassword);
}

?>
