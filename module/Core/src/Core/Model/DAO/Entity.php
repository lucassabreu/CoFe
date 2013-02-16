<?php

namespace Core\Model\DAO;

/**
 * Base class for entities managed by Core\Model\DAOInterface.
 * 
 * @see Core\Model\DAO\DAOInterface
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @MappedSuperclass
 */
interface Entity extends \Serializable {

    /**
     * Retrives the id of entity.
     * @return array|mixed
     */
    public function getId();

    /**
     * Set the ID of entity.
     * @param mixed... $id Id of entity.
     * @return Entity this
     */
    public function setId();

    /**
     * Retrieves the entity in array format
     * @return array
     */
    public function toArray();

    /**
     * Load the attributes of entoty by array.
     * @param array $values
     * @return Entity this
     */
    public function exchangeData(array $values);
}

?>
