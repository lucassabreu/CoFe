<?php

namespace Application\Model\Doctrine;

use Admin\Model\Entity\User;
use Application\Model\DAO\MovimentDAOInterface;
use Application\Model\Entity\Category;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;
use Core\Model\DAO\Exception\DAOException;

/**
 * Implementation of MovimentDAOInterface for Doctrine
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentDAODoctrine extends AbstractDoctrineDAO implements MovimentDAOInterface {

    public function __construct() {
        parent::__construct('Application\Model\Entity\Moviment');
    }

    public function fetchAllFromCategory(Category $cat) {
        $q = $this->getQuery(array('category' => $cat));
        return $q->getQuery()->execute();
    }

    public function fetchAllOfUser(User $user) {
        $qb = $this->getQuery(array('category.user' => $user));
        return $qb->getQuery()->execute();
    }

    public function moveMoviments(Category $from, Category $to) {
        return null;
    }

    public function fetchCategories(User $user) {
        throw new DAOException('Not Implemented');
    }

    public function findCategory($number) {
        throw new DAOException('Not Implemented');
    }

}

?>
