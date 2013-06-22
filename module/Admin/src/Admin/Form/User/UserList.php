<?php

namespace Admin\Form\User;

use Admin\Model\Entity\User;
use Core\Form\ListForm;
use Core\Model\Entity\Entity;
use Zend\Form\Fieldset;

class UserList extends ListForm {

    protected $time = null;

    public function __construct() {
        parent::__construct('userlist_' . ($this->time = time()));
        $this->setAttribute('method', 'post');
    }

    protected function createFieldsetFor(Entity $entity) {

        $user = $entity;
        /* @var $user User */

        $fieldset = new Fieldset("user_{$user->id}");


        $fieldset->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'id' => "id_$time",
                'value' => $user->getId(),
            ),
        ));

        return $fieldset;
    }

}

?>
