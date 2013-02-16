<?php

namespace Admin\Service;

use Core\Test\ServiceTestCase;

/**
 * Class for test of Admin\Service\AuthService
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @package Admin
 * @subpackage Service
 * 
 * @group Service
 */
class AuthServiceTest extends ServiceTestCase {
    /**
     * Test if the Service has been registered
     */
    public function testAuthentificationServiceRegister(){
        $service = $this->getService('Admin\Service\AuthService');
    }
    
    
}

?>
