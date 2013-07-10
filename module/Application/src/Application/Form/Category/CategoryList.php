<?php

namespace Application\Form\Category;

use Core\Form\Form;

/**
 * Form used at category list
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryList extends Form {

    public function __construct() {
        parent::__construct('categoryList_' . ($time = time()));
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'code',
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
            'name' => 'movimentsButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "movimentsButton_$time",
            ),
            'options' => array(
                'label' => 'See Moviments',
            ),
        ));


        $this->add(array(
            'name' => 'moveMovimentsButton',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'type' => 'submit',
                'id' => "moveMovimentsButton_$time",
            ),
            'options' => array(
                'label' => 'Move Moviments',
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
