<?php

namespace Core\Model\DAO;

use Core\Model\DAO\DAOInterface;
use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Zend\Paginator\Adapter\AdapterInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Basic interface for DAO classes.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
interface DAOInterface extends ServiceManagerAwareInterface {

    /**
     * Retrieves the name of entity going to be managed by.
     * @return string 
     */
    public function getEntityClassName();

    /**
     * Retrieves the instance of entity based id. 
     * 
     * If ID of class is not-unique attribute, that that can be passed by two
     * ways:
     * 
     *  DAOInterface::findById($id1, $id2, $id...);
     *  
     *  or
     *      
     *  DAOInterface::findById(array($id1, $id2, $id...));
     * 
     * @param array|mixed $_ Sequence of params to identify entity.
     * @return Entity
     */
    public function findById();

    /**
     * Insert or update one entity.
     * @param Entity $ent
     * @return Entity
     * 
     * @throws DAOException Error in entity values.
     */
    public function save(Entity $ent);

    /**
     * Removes the param entity.
     * @param Entity $ent
     * @return DAOInterface
     * 
     * @throws DAOException Error of relation or state.
     */
    public function remove(Entity $ent);

    /**
     * Retrieves all entries of entity.
     * @return Entity[] Entities
     */
    public function fetchAll($limite = null, $initial = null);

    /**
     * Retrieves a array of entities based on params.
     * @param array $params
     * @param integer $limite
     * @param integer $offset
     * @return Entity[] Entities
     */
    public function fetchByParams(array $params, $limite = null, $offset = null);

    /**
     * Returns a Paginator Adapter based on params.
     * @param array|mixed $params
     * @return AdapterInterface
     */
    public function getAdapterPaginator($params, $orderBy = null);
}

?>
