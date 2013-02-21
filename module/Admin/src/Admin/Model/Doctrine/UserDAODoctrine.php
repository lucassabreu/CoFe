<?php

namespace Admin\Model\Doctrine;

use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;

/**
 * Description of UserDAODoctrine
 *
 * @author Lucas
 */
class UserDAODoctrine extends AbstractDoctrineDAO {

    public function __construct() {
        parent::__construct('Admin\Model\Entity\User');
    }

}

?>
