<?php

namespace ApplicationTest\Model\Entity;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Core\Test\ModelTestCase;

/**
 * Class for tests with the implementation Category Entity.
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @group Category
 * @group CategoryTest
 */
class CategoryTest extends ModelTestCase {

    public function __construct() {
        $this->daoName = 'Application\Service\CategoryDAOService';
    }

    /**
     * Test implementation of InputFilter to attributes
     */
    public function testInputFilterImplementation() {
        $category = new Category();
        $this->assertInstanceOf('Zend\InputFilter\InputFilterAwareInterface', $category);

        $inputFilter = $category->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $inputFilter);

        $data = $category->getInputFilter()->getRawValues();

        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('flowType', $data);
        $this->assertArrayHasKey('parent', $data);
        $this->assertArrayHasKey('child', $data);
    }

    /**
     * Test implementation of filters to attributes
     */
    public function testAttributesFilterRules() {
        $category = new Category();

        $category->setCode("       Cat<br/>ego");
        $this->assertEquals("CATEGO", $category->getCode());

        $category->setCode("catego");
        $this->assertEquals("CATEGO", $category->getCode());

        $category->setCode("CATEGO");
        $this->assertEquals("CATEGO", $category->getCode());

        $category->setDescription("       Cat<br/>egory");
        $this->assertEquals("Category", $category->getDescription());

        $category->setDescription("Category");
        $this->assertEquals("Category", $category->getDescription());

        $category->setFlowType("1");
        $this->assertEquals(1, $category->getFlowType());

        $category->setFlowType(1);
        $this->assertEquals(1, $category->getFlowType());

        $category->setFlowType("0");
        $this->assertEquals(0, $category->getFlowType());

        $category->setFlowType(0);
        $this->assertEquals(0, $category->getFlowType());
    }

    /**
     * Test implementation of validation to attributes.
     * @expectedException \Exception
     */
    public function testAtributesValidationRulesCategoryUserParentDifferent() {
        $user01 = $this->retrieveUser("user01");
        $user02 = $this->retrieveUser("user02");

        //$cat01 = new Category();
        //$cat01->setCode('code01');
        //$cat01->setUser($user01);

        $cat02 = new Category();
        $cat02->setCode('code02');
        $cat02->setUser($user02);

        //$cat01->addChild($cat02);
    }

    public function testAddChild() {
        $cat01 = new Category(null, "CAT01");
        $cat02 = new Category(null, "CAT02");

        $user01 = $this->retrieveUser('user01');
        $user02 = $this->retrieveUser('user02');

        //$cat01->setUser($user01);
        $cat02->setUser($user02);
        $cat02->setParent($cat01);
        
        echo "$cat01\n";
        echo "$cat02\n";
    }

    /**
     * Test implementation of validation to attributes.
     * @expectedException \Exception
     * @expectedExceptionMessage Input inválido: code = 'Nequeporroquis'. The input is more than 6 characters long
     */
    public function testAtributesValidationRulesCodeLessLength() {
        $cat = new Category();
        $cat->setCode("Nequeporroquis");
    }

    /**
     * Test implementation of validation to attributes.
     * @expectedException \Exception
     * @expectedExceptionMessage Input inválido: code = 'Ne'. The input is less than 3 characters long
     */
    public function testAtributesValidationRulesCodeGreaterLength() {
        $cat = new Category();
        $cat->setCode("Ne");
    }

    /**
     * Test implementation of validation to attributes.
     * @expectedException \Exception
     * @expectedExceptionMessage Input inválido: code = ''. Value is required and can't be empty, The input is an empty string, The input is less than 3 characters long
     */
    public function testAtributesValidationRulesCodeEmptyString() {
        $cat = new Category();
        $cat->setCode('');
    }

    /**
     * Retrieves a filled User
     * @param string $name
     * @return User
     */
    public function retrieveUser($name) {
        $user = new User();
        $user->setUsername($name);
        $user->setPassword(md5($name));
        $user->setName($name);
        $user->setActive(1);
        $user->setRole('admin');
        $user->setEmail("$name@localhost.net");

        $dao = $this->getService('Admin\Service\UserDAOService');
        /** @var $dao Admin\Service\UserDAOService */
        $user = $dao->save($user);

        return $user;
    }

}

?>
