<?php

namespace Core\Form;

use Core\Form\Form;
use Core\Model\Entity\Entity;
use Zend\Form\Fieldset;
use Zend\Paginator\Paginator;

/**
 * Base class for list forms
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
abstract class ListForm extends Form {

    /**
     * Paginator with Entitys to list
     * @var Paginator
     */
    protected $paginator = null;

    public function __construct(Paginator $paginator, $name = null, $options = array()) {
        parent::__construct($name, $options);
        $this->paginator = $paginator;

        foreach ($this->paginator as $entity) {
            /* @var $entity Entity */
            $this->add($this->createFieldsetFor($entity));
        }
    }

    /**
     * Create a <code>Fielset</code> based on param entity.
     * @param $entity Entity base
     * @return Fieldset 
     */
    abstract protected function createFieldsetFor(Entity $entity);

    /**
     * Retrieves the paginator of list
     * @return Paginator
     */
    public function getPaginator() {
        return $this->paginator;
    }

}

?>
