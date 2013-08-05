<?php

namespace Application\Form\Category;

use Application\Model\Entity\Category;

/**
 * Form for Category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryForm extends CategoryList {

    protected $time;

    public function __construct($name) {
        $time = $this->time = time();
        parent::__construct($name);

        $c = new Category();
        $if = $c->getInputFilter();

        $if->remove('child');
        $if->remove('parent');
        $if->remove('user');

        $if->add(array(
            'name' => 'parent',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));

        $this->setInputFilter($if);

        $this->add(array(
            'name' => 'code',
            'attributes' => array(
                'id' => "code_$time",
                'type' => 'text',
                'placeholder' => 'Category code',
                'maxlength' => 6,
                'required' => true,
            ),
            'options' => array(
                'label' => 'Code',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'id' => "description_$time",
                'type' => 'text',
                'placeholder' => 'Category description',
                'required' => true,
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));

        $this->add(array(
            'name' => 'flowType',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => "flowType_$time",
                'required' => true,
                'options' => array(
                    0 => 'Output',
                    1 => 'Input',
                ),
            ),
            'options' => array(
                'label' => 'Flow Type',
            ),
        ));

        $this->add(array(
            'name' => 'parent',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => "parent_$time",
                'name' => 'parent',
                'required' => false,
            ),
            'options' => array(
                'label' => 'Parent',
            ),
        ));

        $this->getInputFilter()->remove('parent');
        $this->getInputFilter()->add(array(
            'name' => 'parent',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));
    }

}

?>
