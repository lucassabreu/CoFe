<?php

namespace Application\Form\Category;

/**
 * Form for remove category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryRemove extends CategoryForm {

    public function __construct() {
        parent::__construct("removeCategory_" . ($time = $this->time = time()));

        $this->remove('flowType');
        $this->remove('parent');
        $this->remove('createButton');
        $this->remove('detailButton');
        $this->remove('editButton');
        $this->remove('movimentsButton');
        $this->remove('moveMovimentsButton');
        $this->remove('removeButton');

        $this->add(array(
            'name' => 'submitAction',
            'type' => 'submit',
            'attributes' => array(
                'id' => "submitAction_$time",
                'value' => 'remove',
            ),
            'options' => array(
                'label' => 'Remove',
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
