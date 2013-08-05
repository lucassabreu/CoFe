<?php

use Application\Form\Category\CategoryForm;

namespace Application\Form\Category;

/**
 * Form for creation of Category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryCreate extends CategoryForm {

    public function __construct() {
        parent::__construct('createCategory_' . ($time = $this->time));
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'submitAction',
            'type' => 'submit',
            'attributes' => array(
                'id' => "submitAction_$time",
                'value' => 'create',
            ),
            'options' => array(
                'label' => 'Create',
            ),
        ));

        $this->add(array(
            'name' => 'cancel',
            'type' => 'submit',
            'attributes' => array(
                'id' => "cancel_$time",
                'value' => 'cancel',
            ),
            'options' => array(
                'label' => 'Cancel',
            ),
        ));
    }

}

?>
