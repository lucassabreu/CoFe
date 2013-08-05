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

    public function findById() {
        $params = func_get_args();

        if (count($params) === 1 && is_array($params[0]))
            $params = $params[0];

        if (count($params) > 2)
            throw new DAOException(sprintf('Method %s, has signature: %s($userOrNumber, $code = null).', __METHOD__, __METHOD__));

        if (count($params) == 1)
            return parent::findById($params[0]);
        else {
            $q = $this->getQuery(
                    array(
                        'user' => $params[0],
                        'code' => $params[1]
            ));

            $result = $q->getQuery()->execute();

            if (count($result) === 1)
                return $result[0];
            else
                return null;
        }
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
