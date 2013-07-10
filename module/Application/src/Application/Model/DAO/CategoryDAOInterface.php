<?php

namespace Application\Model\DAO;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Core\Model\DAO\DAOInterface;

/**
 * Interface contract for DAO objects for class <code>Category</code>
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see Category
 */
interface CategoryDAOInterface extends DAOInterface {

    /**
     * Retrieves all entries of categories which has no parent.
     * @return Category[] Categories
     */
    public function fetchAllTop(User $user = null);
}

?>
