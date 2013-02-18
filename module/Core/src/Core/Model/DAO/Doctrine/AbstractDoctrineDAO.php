<?php

namespace Core\Model\Doctrine\DAO;

use Core\Model\DAO\DAOInterface;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;
use Core\Model\DAO\Entity;
use Core\Service\Service;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Basic implemented abstract class for DAO based on Doctrine
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @abstract
 */
abstract class AbstractDoctrineDAO extends Service implements DAOInterface {

    private $className = null;

    /**
     * Retrieve the instance of EntityManager
     * @return EntityManager
     */
    public static function getEntityManager() {
        return $this->getService('Doctrine\ORM\EntityManager');
    }

    /**
     * Retrieves the Repository relative to managed entity.
     * @return EntityRepository
     */
    public static function getRepository() {
        return $this->getEntityManager()->getRepository($this->getEntityClassName());
    }

    /**
     * Constructor of class
     * @param string $className Name of class going to manage.
     */
    public function __construct($className) {
        $this->setEntityClassName($className);
    }

    public function fetchAll($limite = null, $offset = null) {
        if ($limite === null && $offset === null) {
            return $this->getRepository()->findAll();
        }

        return $this->getRepository()->findBy(array(), null, $limite, $offset);
    }

    public function fetchByParams(array $params, $limite = null, $offset = null) {
        return $this->getRepository()->findBy($params, null, $limite, $offset);
    }

    public function findById() {
        $id = func_get_args();

        if (count($id) == 1 && is_array($id[0]))
            return $this->getRepository()->find($id[0]);
        else
            return $this->getRepository()->find($id);
    }

    public function save(Entity $ent) {
        $ent = $this->getEntityManager()->persist($ent);
        $this->getEntityManager()->flush($ent);
        return $ent;
    }

    public function remove(Entity $ent) {
        $ent = $this->getEntityManager()->remove($ent);
        $this->getEntityManager()->flush($ent);
        return $ent;
    }

    /**
     * Set the entity's class name going to manage.
     * @param string $className
     * @return AbstractDoctrineDAO
     */
    protected function setEntityClassName($className) {
        $this->className = $className;
        return $this;
    }

    public function getEntityClassName() {
        return $this->className;
    }

}

?>
