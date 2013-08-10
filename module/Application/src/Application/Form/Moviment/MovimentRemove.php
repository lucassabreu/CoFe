<?php

namespace Application\Form\Moviment;

/**
 * Form for remove Moviment
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentRemove extends MovimentForm {

    public function __construct() {
        parent::__construct();
        $this->setAttribute('name', "removeMoviment_$this->time");

        $this->remove('createButton');
        $this->remove('detailButton');
        $this->remove('updateButton');
        $this->remove('removeButton');

        $this->remove("category");
        $this->remove("dateEmission");
        $this->remove("value");
        $this->remove("description");
        $this->remove("notes");

        $this->get('submitAction')->setOptions(array('label' => 'Remove'));
        $this->get('submitAction')->setLabel('Remove');
        $this->get('submitAction')->setValue('remove');
    }

}

?>
