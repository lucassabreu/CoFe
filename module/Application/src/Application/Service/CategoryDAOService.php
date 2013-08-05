<?php

namespace Application\Service;

use Admin\Model\Entity\User;
use Application\Model\DAO\CategoryDAOInterface;
use Application\Model\Entity\Category;
use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Service\AbstractDAOService;

/**
 * Service used by manage category entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @see Category
 */
class CategoryDAOService extends AbstractDAOService implements CategoryDAOInterface {

    public function save(Entity $ent) {
        $category = null;

        if ($ent instanceof Category)
            $category = $ent;
        else
            throw new DAOException("The service " . __CLASS__ . " not manage the class " . get_class($ent));

        $otherCategory = $this->findById($category->getUser(), $category->getCode());

        if ($otherCategory !== null && $otherCategory->getNumber() !== $category->getNumber())
            throw new DAOException(sprintf("Category with code %s already exists.", $category->getCode()));

        if (!$category->getInputFilter()->get('parent')->isValid())
            throw new DAOException(sprintf("Parent of category is invalid"));

        if (!$category->getNumber() == null) {
            $categoryOld = $this->findById($category->getNumber());

            if ($categoryOld->getUser()->getId() !== $category->getUser()->getId())
                throw new DAOException("User of category can't be changed");

            if ($categoryOld !== $category) {
                $categoryOld->setData($category->getData());
                $category = $categoryOld;
            }
        }

        $ent->setData($category->getData());
        return parent::save($category);
    }

    /**
     * Retrives a Category based on param key
     * @param User|int $userOrNumber User or number of category
     * @param string $code (optional) Code of category
     * @return Category
     * @throws DAOException Invalid number of params
     */
    public function findById() {
        $params = func_get_args();

        if (count($params) > 2)
            throw new DAOException(sprintf('Method %s, has signature: %s($userOrNumber, $code = null).', __METHOD__, __METHOD__));

        if (count($params) == 1)
            return parent::findById($params[0]);
        else {
            return parent::findById($params[0], $params[1]);
        }
    }

    public function fetchAllTop(User $user = null) {
        return $this->dao->fetchAllTop($user);
    }

}

?>
