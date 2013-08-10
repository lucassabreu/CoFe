<?php

namespace Application\Service;

use Admin\Model\Entity\User;
use Application\Model\DAO\MovimentDAOInterface;
use Application\Model\Entity\Category;
use Application\Model\Entity\Moviment;
use Core\Model\DAO\Exception\DAOException;
use Core\Service\AbstractDAOService;

/**
 * Service used by manage moviment entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see Moviment
 */
class MovimentDAOService extends AbstractDAOService implements MovimentDAOInterface {

    public function moveMoviments(Category $from, Category $to) {
        $categoryDao = $this->getService('Application\Service\CategoryDAOService');
        /* @var $categoryDao \Application\Service\CategoryDAOService */

        $from = $categoryDao->findById($from->getNumber());
        $to = $categoryDao->findById($to->getNumber());

        if (is_null($from))
            throw new DAOException('');
    }

    public function fetchAllFromCategory(Category $cat) {
        return $this->dao->fetchAllFromCategory($cat);
    }

    public function fetchAllOfUser(User $user) {
        return $this->dao->fetchAllOfUser($user);
    }

    public function fetchCategories(User $user) {
        $daoCat = $this->getService('Application\Service\CategoryDAOService');
        /* @var $daoCat \Application\Service\CategoryDAOService */

        return $daoCat->fetchAllTop($user);
    }

    public function findCategory($number) {
        $daoCat = $this->getService('Application\Service\CategoryDAOService');
        /* @var $daoCat \Application\Service\CategoryDAOService */

        return $daoCat->findById($number);
    }

}

?>
