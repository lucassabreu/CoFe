<?php

namespace Core\Model\DAO\Doctrine;

use Core\Model\DAO\DAOInterface;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;
use Core\Model\Entity\Entity;
use Core\Service\Service;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

/**
 * Basic implemented abstract class for DAO based on Doctrine
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * @abstract
 */
abstract class AbstractDoctrineDAO extends Service implements DAOInterface {

    /**
     * @var array
     */
    private $_idColumns = null;

    /**
     * @var string
     */
    private $className = null;

    /**
     * Retrieve the instance of EntityManager
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->getService('Doctrine\ORM\EntityManager');
    }

    /**
     * Retrieves the Repository relative to managed entity.
     * @return EntityRepository
     */
    public function getRepository() {
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
        $query = $this->getQuery(null, $limite, $offset)->getQuery();
        return $query->execute();
    }

    public function fetchByParams(array $params, $limite = null, $offset = null) {
        $query = $this->getQuery($params, $limite, $offset)->getQuery();
        return $query->execute();
    }

    /**
     * Returns a query to execute based on params.
     * @param array|mixed $params
     * @param integer $limit
     * @param integer $offset
     * @return QueryBuilder
     */
    protected function getQuery($params = null, $limit = null, $offset = null) {
        $qb = $this->getRepository()->createQueryBuilder('ent');

        if ($params !== null) {
            if (is_array($params)) {
                if (count($params) !== 0) {
                    /**
                     * @var string[]
                     */
                    $qbParams = array();

                    $and = $qb->expr()->andX();

                    foreach ($params as $column => $clause) {
                        if (is_array($clause)) {
                            $and->add($qb->expr()->between('ent.' . $column, "?" . count($qbParams), "?" . (count($qbParams) + 1)));
                            $qbParams[] = $clause[0];
                            $qbParams[] = $clause[1];
                        } else {
                            if (strpos($clause, '%') > 0)
                                $and->add($qb->expr()->like('ent.' . $column, "?" . count($qbParams)));
                            else
                                $and->add($qb->expr()->eq('ent.' . $column, "?" . count($qbParams)));
                            
                            $qbParams[] = $clause;
                        }
                    }

                    $qb->where($and);
                    $qb->setParameters($qbParams);
                }
            } else
                $qb->where($params);
        }

        if ($limit != null)
            $qb->setMaxResults($limit);

        if ($offset != null)
            $qb->setFirstResult($offset);

        return $qb;
    }

    public function getAdapterPaginator($params, $orderBy = null) {
        $qb = $this->getQuery($params);

        if ($orderBy != null)
            foreach ($orderBy as $column => $order)
                $qb->orderBy($column, $order);

        $paginator = new Paginator($qb->getQuery());
        $adapter = new DoctrinePaginator($paginator);

        return $adapter;
    }

    public function findById() {
        $id = func_get_args();

        if (count($id) == 1 && is_array($id[0]))
            return $this->find($id[0]);
        else
            return $this->find($id);
    }

    protected function find(array $id) {
        $keys = $this->getIdColumns();

        $nId = array();

        foreach ($id as $num => $value) {
            $nId[$keys[$num]] = $value;
        }

        return $this->getRepository()->find($nId);
    }

    public function save(Entity $ent) {
        $this->getEntityManager()->persist($ent);
        $this->getEntityManager()->flush($ent);
        return $ent;
    }

    public function remove(Entity $ent) {
        $this->getEntityManager()->remove($ent);
        $this->getEntityManager()->flush($ent);
        return $this;
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

    protected function getIdColumns() {
        if ($this->_idColumns === null) {
            $this->_idColumns = $this->getEntityManager()->getClassMetadata('\\' . $this->getEntityClassName())->identifier;
        }

        return $this->_idColumns;
    }

}

?>
