<?php

namespace Admin\Form\User;

use Core\Form\Form;

class Remove extends Form {

    public function __construct() {
        parent::__construct('userRemove_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "id_$time",
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'id' => "username_$time",
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => "name_$time",
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'submitRemove',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "submitRemove_$time",
                'value' => 'true',
            ),
            'options' => array(
                'label' => 'Remove',
            ),
        ));

        $this->add(array(
            'name' => 'submitCancel',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "submitCancel_$time",
            ),
            'options' => array(
                'label' => 'Cancel',
            ),
        ));
    }

}
?>