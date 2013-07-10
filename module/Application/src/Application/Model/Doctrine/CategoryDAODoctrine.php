<?php

namespace Application\Model\Doctrine;

use Admin\Model\Entity\User;
use Application\Model\DAO\CategoryDAOInterface;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;

/**
 * Implementation of CategoryDAOInterface for Doctrine
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryDAODoctrine extends AbstractDoctrineDAO implements CategoryDAOInterface {

    public function __construct() {
        parent::__construct('Application\Model\Entity\Category');
    }

    public function fetchAllTop(User $user = null) {
        $params = array('parent' => null);

        if ($user !== null)
            $params['user'] = $user;

        $query = $this->getQuery($params)->getQuery();

        return $query->execute();
    }

}

?>