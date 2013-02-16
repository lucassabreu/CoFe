<?php

namespace Core\Model\DAO;

use Core\Model\DAO\Entity;
use Core\Model\DAO\Exception\DAOException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Basic interface for DAO classes.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
interface DAOInterface extends ServiceLocatorAwareInterface {

    /**
     * Retrieves the name of entity going to be managed by.
     * @return string 
     */
    public function getEntityClassName();

    /**
     * Retrieves the instance of entity based id
     * @param mixed ... $id Sequence of params to identify entity.
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
     * @return Entity
     * 
     * @throws DAOException Error of relation or state.
     */
    public function remove(Entity $ent);
    
    /**
     * Retrieves all entries of entity.
     * @return array Entities
     */
    public function fetchAll($limite = null, $initial = null);
    
    /**
     * 
     * @param array $params
     * @param integer $limite
     * @param integer $offset
     */
    public function fetchByParams(array $params, $limite = null, $offset = null);
    
}

?>
