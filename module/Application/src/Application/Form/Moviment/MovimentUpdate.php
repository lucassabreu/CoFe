<?php

namespace Application\Form\Moviment;

/**
 * Form for update moviment
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentUpdate extends MovimentForm {

    public function __construct() {
        parent::__construct();
        $this->setAttribute('id', 'updateMoviment_' . ($this->time));

        $this->get('submitAction')->setOptions(array('label' => 'Update'));
        $this->get('submitAction')->setLabel('Update');
        $this->get('submitAction')->setValue('update');
    }

}

?>
