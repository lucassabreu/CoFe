<?php

namespace Application\Form\Category;

/**
 * Form for detail of Category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryDetail extends CategoryForm {

    public function __construct() {
        parent::__construct('detailCategory_' . ($time = $this->time = time()));

        $this->remove('submitAction');
        $this->remove('cancel');

        $this->remove('parent');

        $this->add(array(
            'name' => 'parent_number',
            'attributes' => array(
                'name' => 'parent_number',
                'id' => "parent_number_$time",
                'type' => 'number',
            ),
            'options' => array(
                'label' => 'Parent',
            ),
        ));

        $this->add(array(
            'name' => 'parent_code',
            'attributes' => array(
                'name' => 'parent_code',
                'id' => "parent_code_$time",
            ),
            'options' => array(
                'label' => 'Parent Code',
            ),
        ));

        $this->add(array(
            'name' => 'parent_description',
            'attributes' => array(
                'name' => 'parent_description',
                'id' => "parent_description_$time",
            ),
            'options' => array(
                'label' => 'Parent Description',
            ),
        ));

        $this->add(array(
            'name' => 'parentDetailButton',
            'type' => 'submit',
            'attributes' => array(
                'id' => "parentDetailButton_$time"
            ),
            'options' => array(
                "label" => "Detail Parent",
            ),
        ));
    }

}

?>
