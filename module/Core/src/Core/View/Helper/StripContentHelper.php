<?php

namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper for strip content for less data send
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class StripContentHelper extends AbstractHelper {

    public function __invoke($content) {
        return $content;
        return str_replace('> <', '><', preg_replace('(\\s+)', ' ', $content));
    }

}

?>
