<?php

namespace Admin\Form\User;

use Core\Form\Form as FormBase;
use Zend\InputFilter\Factory;

class ChangePassword extends FormBase {

    public function __construct() {
        parent::__construct('changePassword_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $factory = new Factory();
        $this->setInputFilter($factory->createInputFilter(array(
                    'oldPassword' => array(
                        'name' => 'oldPassword',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array('name' => 'NotEmpty'),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'min' => 8,
                                    'max' => 32
                                ),
                            ),
                        ),
                    ),
                    'newPassword' => array(
                        'name' => 'newPassword',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array('name' => 'NotEmpty'),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'min' => 8,
                                    'max' => 32
                                ),
                            ),
                        ),
                    ),
                    'confirmPassword' => array(
                        'name' => 'confirmPassword',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array('name' => 'NotEmpty'),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'min' => 8,
                                    'max' => 32
                                ),
                            ),
                        ),
                    ),
        )));

        $this->add(array(
            'name' => 'oldPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "oldPassword_$time",
                'placeholder' => 'Inform your actual password'
            ),
            'options' => array(
                'label' => 'Old Password',
            ),
        ));

        $this->add(array(
            'name' => 'newPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "newPassword_$time",
                'placeholder' => 'Inform the new password',
            ),
            'options' => array(
                'label' => 'New Password',
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "confirmPassword_$time",
                'placeholder' => 'Confirm the new password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));

        $this->add(array(
            'name' => 'submitButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "submitButton_$time",
                'value' => 'changePassword',
            ),
            'options' => array(
                'label' => 'Change Password',
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