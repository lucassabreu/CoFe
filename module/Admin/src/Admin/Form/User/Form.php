<?php

namespace Admin\Form\User;

use Admin\Model\Entity\User as UserEntity;
use Core\Form\Form as FormBase;

/**
 * Detail class for entity User
 * 
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class Form extends FormBase {

    public function __construct($create = false) {
        parent::__construct('user_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $user = new UserEntity();
        $if = $user->getInputFilter();

        //if ($create === false)
        $if->remove('password');

        if ($create) {
            $if->remove('id');
            $if->remove('active');
        }

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
            'name' => 'name',
            'attributes' => array(
                'id' => "name_$time",
                'type' => 'text',
                'placeholder' => 'Name of the new user',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'id' => "email_$time",
                'type' => 'email',
                'placeholder' => 'E-mail which to associate with the system',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'id' => "username_$time",
                'type' => 'text',
                'placeholder' => 'Username used on login'
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => "role_$time",
                'options' => array(
                    'guest' => 'Guest',
                    'admin' => 'Administrator',
                    'common' => 'Common',
                ),
            ),
            'options' => array(
                'label' => 'Role',
            ),
        ));

        if (!$create)
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
            'name' => 'dateCreation',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'id' => "dateCreation_$time",
                'type' => 'date',
                'step' => 'any',
            ),
            'options' => array(
                'label' => 'Date Creation',
            ),
        ));

        $this->add(array(
            'name' => 'submitAction',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "submitAction_$time",
                'value' => 'update',
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "cancelbutton_$time",
                'value' => 'cancel',
            ),
            'options' => array(
                'label' => 'Cancel',
            ),
        ));

        if (!$create)
            $this->add(array(
                'name' => 'changePassword',
                'type' => 'Zend\Form\Element\Button',
                'attributes' => array(
                    'type' => 'button',
                    'id' => "cancelbutton_$time",
                    'value' => 'changePassword',
                ),
                'options' => array(
                    'label' => 'Change Password',
                ),
            ));
    }

}