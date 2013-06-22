<?php

namespace Admin\Model\DAO;

use Admin\Model\Entity\User;
use Core\Model\DAO\DAOInterface;

/**
 * Interface contract for DAO objects for class User
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see User
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
     * @return User
     */
    public function changePassword(User $user, $oldPassword, $newPassword);

    /**
     * Alter the <code>active</code> status of User to inactive (locked).
     * @param User $user
     * @return User User updated
     */
    public function lock(User $user);

    /**
     * Alter the <code>active</code> status of User to active (unlocked).
     * @param User $user
     * @return User User updated
     */
    public function unlock(User $user);
}

?>
