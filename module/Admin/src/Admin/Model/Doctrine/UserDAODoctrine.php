<?php

namespace Admin\Model\Doctrine;

use Admin\Model\DAO\UserDAOInterface;
use Admin\Model\Entity\User;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;

/**
 * Implementation of UserDAOInterface for Doctrine
 *
 * @author Lucas dos Santos Abreu
 */
class UserDAODoctrine extends AbstractDoctrineDAO implements UserDAOInterface {

    public function __construct() {
        parent::__construct('Admin\Model\Entity\User');
    }

    public function changePassword(User $user, $oldPassword, $newPassword) {
        return $this->save($user);
    }
    
    public function resetPasswordTo(User $user, $password) {
        return $this->save($user);
    }

    public function findByUsername($username) {
        $qb = $this->getRepository()->createQueryBuilder('u');

        $qb->select('u')
                ->where($qb->expr()->eq('u.username', ':username'))
                ->setParameter('username', $username);

        $user = $qb->getQuery()->getOneOrNullResult();

        return $user;
    }

    public function lock(User $user) {
        return $this->save($user);
    }

    public function unlock(User $user) {
        return $this->save($user);
    }

}

?>
