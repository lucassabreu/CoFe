<?php

namespace Application\Form\Moviment;

/**
 * Form detail for moviment
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentDetail extends MovimentForm {
    public function __construct() {
        parent::__construct();
        $this->setAttribute('id', "detailMoviment_$this->time");
        
        $this->remove('submitAction');
        $this->remove('cancel');

        $this->remove('category');

        $this->add(array(
            'name' => 'category_number',
            'attributes' => array(
                'name' => 'category_number',
                'id' => "category_number_$this->time",
                'type' => 'number',
            ),
            'options' => array(
                'label' => 'Category',
            ),
        ));

        $this->add(array(
            'name' => 'category_code',
            'attributes' => array(
                'name' => 'category_code',
                'id' => "category_code_$this->time",
            ),
            'options' => array(
                'label' => 'Category Code',
            ),
        ));

        $this->add(array(
            'name' => 'category_description',
            'attributes' => array(
                'name' => 'category_description',
                'id' => "category_description_$this->time",
            ),
            'options' => array(
                'label' => 'Category Description',
            ),
        ));

        $this->add(array(
            'name' => 'categoryDetailButton',
            'type' => 'submit',
            'attributes' => array(
                'id' => "categoryDetailButton_$this->time"
            ),
            'options' => array(
                "label" => "Detail Category",
            ),
        ));
    }    
}

?>
