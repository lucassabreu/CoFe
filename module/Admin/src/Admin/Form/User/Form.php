<?php

namespace Admin\Form\User;

use Admin\Model\Entity\User as UserEntity;
use Core\Form\Form as FormBase;
use Zend\InputFilter\Factory;

class Form extends FormBase {

    public function __construct($enablePassword = false) {
        parent::__construct('user_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $factory = new Factory();
        $user = new UserEntity();
        $if = $user->getInputFilter();

        if ($enablePassword === false)
            $if->remove('password');

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


        if ($enablePassword) {
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
        }

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
                    'admin' => 'Administrator',
                    'commun' => 'Common',
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
                'id' => "submitbutton_$time",
            ),
            'options' => array(
                'label' => 'Update',
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "cancelbutton_$time",
            ),
            'options' => array(
                'label' => 'Cancel',
            ),
        ));

        $this->add(array(
            'name' => 'changePassword',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'button',
                'id' => "cancelbutton_$time",
            ),
            'options' => array(
                'label' => 'Change Password',
            ),
        ));
    }

}