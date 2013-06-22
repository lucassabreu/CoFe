<?php

namespace Admin\Form\User;

use Core\Form\Form;

class Remove extends Form {

    public function __construct() {
        parent::__construct('userRemove_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $this->setInputFilter($if);

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
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "submitbutton_$time",
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "cancelbutton_$time",
            ),
        ));
    }

}
?>