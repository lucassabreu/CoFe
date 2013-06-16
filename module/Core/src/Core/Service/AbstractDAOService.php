<?php

namespace Core\Service;

use Core\Model\DAO\DAOInterface;
use Core\Model\Entity\Entity;
use Core\Service\DAOServiceInterface;
use Core\Service\Service;

/**
 * Abstract class with basic logic to DAO Service classes
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
abstract class AbstractDAOService extends Service implements DAOServiceInterface {

    /**
     * @var DAOInterface
     */
    protected $dao = null;

    public function findById() {
        return $this->dao->findById(func_get_args());
    }

    public function fetchAll($limite = null, $initial = null) {
        return $this->dao->fetchAll($limite, $initial);
    }

    public function fetchByParams(array $params, $limite = null, $offset = null) {
        return $this->dao->fetchByParams($params, $limite, $offset);
    }

    public function getAdapterPaginator($params, $orderBy = null) {
        return $this->getDAOInterface()->getAdapterPaginator($params, $orderBy);
    }

    public function getEntityClassName() {
        return $this->dao->getEntityClassName();
    }

    public function remove(Entity $ent) {
        $this->dao->remove($ent);
        return $this;
    }

    public function save(Entity $ent) {
        $this->dao->save($ent);
        return $ent;
    }

    public function setDAOInterface(DAOInterface $dao) {
        $this->dao = $dao;
        return $this;
    }

    public function getDAOInterface() {
        return $this->dao;
    }

}

?>
