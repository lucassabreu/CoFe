<?php

namespace Application\Form\Moviment;

/**
 * Form for create Moviment
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MovimentCreate extends MovimentForm {

    public function __construct() {
        parent::__construct();
        $this->setAttribute('id', "createMoviment_$this->time");

        $this->remove('createButton');
        $this->remove('detailButton');
        $this->remove('updateButton');
        $this->remove('removeButton');

        $this->get('submitAction')->setOptions(array('label' => 'Create'));
        $this->get('submitAction')->setLabel('Create');
        $this->get('submitAction')->setValue('create');
    }

}

?>
