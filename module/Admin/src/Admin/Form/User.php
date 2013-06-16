<?php

namespace Admin\Form;

use Core\Form\Form;

class User extends Form {

    public function __construct() {
        parent::__construct('user_' . ($time = time()));
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
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "password_$time",
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "confirmPassword_$time",
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));

        $this->add(array(
            'name' => 'dateCreation',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'id' => "dateCreation_$time",
                'type' => 'date',
            ),
            'options' => array(
                'label' => 'Date Creation',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'id' => "email_$time",
                'type' => 'email',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));

        $this->add(array(
            'name' => 'active',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'id' => "active_$time",
                'type' => 'checkbox',
            ),
            'options' => array(
                'label' => 'Active',
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => "role_$time",
                'options' => array(
                    'guest' => 'Guest',
                    'admin' => 'Adminstrator',
                    'common' => 'Common',
                ),
            ),
            'options' => array(
                'label' => 'Role',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Send',
                'id' => "submitbutton_$time",
            ),
        ));


        $this->add(array(
            'name' => 'reset',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Reset',
                'id' => "resetbutton_$time",
            ),
        ));
    }

}