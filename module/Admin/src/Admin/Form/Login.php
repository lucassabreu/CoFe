<?php

namespace Admin\Form;

use Core\Form\Form;
use Zend\InputFilter\Factory;

class Login extends Form {

    public function __construct() {

        parent::__construct('login');
        $this->setAttribute('method', 'post');

        $factory = new Factory();

        $this->setInputFilter(
                $factory->createInputFilter(array(
                    'username' => array(
                        'name' => 'username',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToLower'),
                            array('name' => 'StripTags'),
                        ),
                    ),
                    'password' => array(
                        'name' => 'password',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                    )
                ))
        );

        $this->add(array(
            'name' => 'username',
            'required' => true,
            'attributes' => array(
                'id' => 'username',
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'attributes' => array(
                'id' => 'password',
                'type' => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'id' => 'submitbutton',
            ),
        ));
    }

}