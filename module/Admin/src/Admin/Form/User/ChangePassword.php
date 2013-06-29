<?php

namespace Admin\Form\User;

use Admin\Model\Entity\User as UserEntity;
use Core\Form\Form as FormBase;
use Zend\InputFilter\Factory;

class ChangePassword extends FormBase {

    public function __construct($create = false) {
        parent::__construct('changePassword_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $factory = new Factory();
        $this->setInputFilter($factory->createInputFilter(array(
                    'oldPassword' => array(
                        'name' => 'password',
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
                        'name' => 'password',
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
                        'name' => 'password',
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
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => "id_$time",
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'oldPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "oldPassword_$time",
            ),
        ));

        $this->add(array(
            'name' => 'newPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "newPassword_$time",
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'id' => "confirmPassword_$time",
            ),
        ));

        $this->add(array(
            'name' => 'changePassword',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'button',
                'id' => "changePasswordButton_$time",
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