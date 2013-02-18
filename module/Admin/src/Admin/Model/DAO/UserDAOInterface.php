<?php

namespace Admin\Model\DAO;

use Admin\Model\Entity\User;
use Core\Model\DAO\DAOInterface;

/**
 * Interface contract for DAO objects for class User
 * 
 * @see \Admin\Model\User
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
interface UserDAOInterface extends DAOInterface {
    /**
     * Retrieves, if exists, the User the username from param
     * @param string $username
     * @return User
     */
    public function findByUsername($username);
    
    /**
     * Alter the password of User
     * @param User $user
     * @param string $oldPassword
     * @param string $newPassword
     */
    public function changePassword(User $user, $oldPassword, $newPassword);
}

?>
