<?php

namespace Admin\Form\User;

use Core\Form\Form as FormBase;

class ResetPassword extends FormBase {

    public function __construct() {
        parent::__construct('resetPassword_' . ($time = time()));
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
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "name_$time",
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "username_$time",
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'resetPassword',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "resetPasswordButton_$time",
                'value' => 'resetPassword',
            ),
            'options' => array(
                'label' => 'Reset Password',
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
    }

}