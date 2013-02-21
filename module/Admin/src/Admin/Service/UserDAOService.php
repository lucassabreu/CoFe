<?php

namespace Admin\Service;

use Admin\Model\DAO\UserDAOInterface;
use Admin\Model\Entity\User;
use Core\Model\DAO\Exception\DAOException;
use Core\Service\AbstractDAOService;

/**
 * Service used by manage user entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class UserDAOService extends AbstractDAOService implements UserDAOInterface {

    /**
     * @var Admin\Model\DAO\UserDAOInterface
     */
    protected $dao;

    public function findById() {
        $id = func_get_args();

        if (count($id) != 1)
            throw new DAOException('User key is not composed, uses: ' . __METHOD__ . '($id).');

        parent::findById($id[0]);
    }

    public function findByUsername($username) {
        return $this->dao->findByUsername($username);
    }

    public function changePassword(User $user, $oldPassword, $newPassword) {
        if ($user->id)
            throw new DAOException('To use ' . __METHOD__ . ' the user must be previewsly saved.');

        $userOld = $this->findById($user->id);
        
        if ($userOld)
            throw new DAOException("User with ID '$user->id' not exists.");
        
        
    }

}

?>
