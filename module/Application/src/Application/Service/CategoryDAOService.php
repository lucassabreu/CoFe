<?php

namespace Application\Service;

use Admin\Model\Entity\User;
use Application\Model\DAO\CategoryDAOInterface;
use Application\Model\Entity\Category;
use Core\Service\AbstractDAOService;

/**
 * Service used by manage category entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see Category
 */
class CategoryDAOService extends AbstractDAOService implements CategoryDAOInterface {

    public function fetchAllTop(User $user = null) {
        return $this->dao->fetchAllTop($user);
    }

}

?>
