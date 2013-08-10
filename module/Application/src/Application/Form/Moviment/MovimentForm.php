<?php

namespace Application\Form\Moviment;

use Application\Model\Entity\Moviment;

/**
 * Based form form Moviment
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentForm extends MovimentList {

    public function __construct() {
        parent::__construct();

        $moviment = new Moviment();
        $if = $moviment->getInputFilter();

        $if->remove('category');

        $this->setInputFilter($if);

        $this->add(array(
            'name' => 'category',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => "category_$this->time",
                'name' => 'category',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Category',
            ),
        ));

        $this->add(array(
            'name' => 'value',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'id' => "value_$this->time",
                'name' => 'value',
                'min' => 0,
                'step' => .01,
                'required' => true,
                'placeholder' => 'Value of moviment',
            ),
            'options' => array(
                'label' => 'Value',
            ),
        ));

        $this->add(array(
            'name' => 'dateEmission',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'id' => "dateEmission_$this->time",
                'name' => 'dateEmission',
                'required' => true,
                'placeholder' => 'Date of moviment',
                'type' => 'date',
                'step' => 'any',
            ),
            'options' => array(
                'label' => 'Date Emission',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => "description_$this->time",
                'name' => 'description',
                'required' => true,
                'placeholder' => 'Description of moviment',
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'name' => 'notes',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'id' => "notes_$this->time",
                'name' => 'notes',
                'required' => false,
                'placeholder' => 'Notes of moviment',
            ),
            'options' => array(
                'label' => 'Notes',
            ),
        ));

        $this->add(array(
            'name' => 'submitAction',
            'type' => 'submit',
            'attributes' => array(
                'id' => "submitAction_$this->time",
                'name' => 'submitAction',
                'required' => false,
            ),
            'options' => array(
                'label' => 'Send',
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            'type' => 'submit',
            'attributes' => array(
                'id' => "cancel_$this->time",
                'name' => 'cancel',
                'required' => false,
            ),
            'options' => array(
                'label' => 'Cancel',
            ),
        ));
        
        $this->getInputFilter()->remove('category');
        $this->getInputFilter()->add(array(
            'name' => 'category',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));
    }

}

?>
