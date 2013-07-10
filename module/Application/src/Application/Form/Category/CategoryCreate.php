<?php

namespace Application\Form\Category;

use Core\Form\Form;

/**
 * Form for creation of Category
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class CategoryCreate extends Form {
    public function __construct() {
        parent::__construct('createCategory_' . ($time = time()));
        
        
    }
}

?>
