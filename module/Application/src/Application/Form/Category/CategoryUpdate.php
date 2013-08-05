<?php

namespace Application\Form\Category;

/**
 * Form for update of Category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryUpdate extends CategoryCreate {

    public function __construct() {
        parent::__construct();
        $this->setAttribute('id', 'updateCategory_' . ($time = $this->time));

        $this->remove('submitAction');

        $this->add(array(
            'name' => 'submitAction',
            'type' => 'submit',
            'attributes' => array(
                'id' => "submitAction_$time",
                'value' => 'update',
            ),
            'options' => array(
                'label' => 'Update',
            ),
        ));
    }

}

?>
