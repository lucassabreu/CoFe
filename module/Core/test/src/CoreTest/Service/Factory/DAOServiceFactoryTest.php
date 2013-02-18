<?php

namespace CoreTest\Service\Factory;

use Core\Test\TestCase;

/**
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class DAOServiceFactoryTest extends TestCase {

    public function testDispatchRequest() {
        $this->getService('TEST_LOL');
    }

}

?>
