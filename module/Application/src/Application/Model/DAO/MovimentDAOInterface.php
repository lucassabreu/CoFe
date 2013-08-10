<?php

namespace Application\Model\DAO;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Application\Model\Entity\Moviment;
use Core\Model\DAO\DAOInterface;

/**
 * Interface contract for DAO objects for class <code>Category</code>
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see Category
 */
interface MovimentDAOInterface extends DAOInterface {

    /**
     * Retrieves all entries of moviment from param user.
     * @return Moviment[] Moviments
     */
    public function fetchAllOfUser(User $user);

    /**
     * Retrieves all entries of moviment from param category
     * @return Moviment[] Moviments
     */
    public function fetchAllFromCategory(Category $cat);

    /**
     * Move the moviments from one category, to other. <b>One way only</b>
     * @param Category $from
     * @param Category $to    
     * @return Moviment[] Moviments
     */
    public function moveMoviments(Category $from, Category $to);

    /**
     * Return categories of user
     * @param User $user
     * @return Category[] Categories of user
     */
    public function fetchCategories(User $user);

    /**
     * Retrive a Category by the number
     * @param int $number
     * @return Category 
     */
    public function findCategory($number);
}

?>
