<?php

namespace Core\Form;

use Zend\Form\Form as ZendForm;

/**
 * Implementation of Zend Form with CRUD controls
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class Form extends ZendForm {

    public function readonly() {
        foreach ($this as $element) {
            $element->setAttribute('readonly', 'readonly');
        }
    }

    public function editable() {
        foreach ($this as $element) {
            $element->setAttribute('readonly', null);
        }
    }

}

?>
