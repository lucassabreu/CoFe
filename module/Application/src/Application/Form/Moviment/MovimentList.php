<?php

namespace Application\Form\Moviment;

use Core\Form\Form;

/**
 * Form base for Moviment's List
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentList extends Form {
    protected $time = null;
    
    public function __construct() {
        parent::__construct('movimentList_' . ($this->time = $time = time()));
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'radio',
                'value' => null,
            ),
        ));

        $this->add(array(
            'name' => 'createButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "createButton_$time",
            ),
            'options' => array(
                'label' => 'Create',
            ),
        ));

        $this->add(array(
            'name' => 'detailButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "detailButton_$time",
            ),
            'options' => array(
                'label' => 'Detail',
            ),
        ));

        $this->add(array(
            'name' => 'editButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "editButton_$time",
            ),
            'options' => array(
                'label' => 'Edit',
            ),
        ));

        $this->add(array(
            'name' => 'removeButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "removeButton_$time",
            ),
            'options' => array(
                'label' => 'Remove',
            ),
        ));
    }
}

?>
