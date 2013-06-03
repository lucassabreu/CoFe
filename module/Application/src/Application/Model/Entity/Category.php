<?php

namespace Application\Model\Entity;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Core\Model\Entity\Entity;
use Core\Validator\GenericValidator;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Zend\InputFilter\Factory;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Category Entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category extends Entity {

    /**
     * User owner of Category
     * @ORM\Id
     * @ORM\Column(nullable=false)
     * @var integer 
     */
    private $user;

    /**
     * Identifier of Category at User
     * @ORM\Id
     * @ORM\Column(type="string", length=50, nullable=false)
     * @var string
     */
    private $code;

    /**
     * Description of Category
     * @var string
     */
    private $description;

    /**
     * Flow type of Category
     * @var integer
     */
    private $flowType;

    /**
     * Parent of Category
     * @var Category 
     */
    private $parent;

    /**
     * Childrens of Category
     * @var Category[]
     */
    private $children = array();

    public function getInputFilter() {
        if ($this->inputFilter == null) {
            $factory = new Factory();
            $this->inputFilter = $factory->createInputFilter(
                    array(
                        'user' => array(
                            'name' => 'role',
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function ($value, GenericValidator $validator) {
                                            if ($value == null) {
                                                $validator->setMessage("The user can't be null!");
                                                return false;
                                            }

                                            if (!($value instanceof User)) {
                                                $validator->setMessage("The user must be a instance of Admin\Model\Entity\User!");
                                                return false;
                                            }

                                            if (
                                                    $this->parent != null
                                                    && $this->parent->getUser() != null
                                                    && $this->parent->getUser()->getId() != $value->getId()
                                            ) {
                                                $validator->setMessage("The user on this category must be the same of the parent!");
                                                return false;
                                            }

                                            $this->user = $value;

                                            // propaga para os filhos
                                            foreach ($this->children as $child) {
                                                $child->setUser($value);
                                            }

                                            if ($this->parent != null)
                                                $this->parent->setUser($value);

                                            return true;
                                        },
                                    ),
                                ),
                                array('name' => 'NotEmpty'),
                            ),
                        ),
                        'code' => array(
                            'name' => 'code',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StringToUpper'),
                                array('name' => 'StripTags'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'Alnum',
                                    'options' => array(
                                        'allowWhiteSpace' => false,
                                    ),
                                ),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'min' => 3,
                                        'max' => 6
                                    )
                                ),
                            ),
                        ),
                        'description' => array(
                            'name' => 'description',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StripTags'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'min' => 3,
                                        'max' => 100
                                    )
                                ),
                            ),
                        ),
                        'flowType' => array(
                            'name' => 'id',
                            'filters' => array(
                                array('name' => 'Int')
                            ),
                            'validators' => array(
                                array(
                                    'name' => 'InArray',
                                    'options' => array(
                                        'haystack' => array(0, 1),
                                    ),
                                ),
                            ),
                        ),
                        'parent' => array(
                            'name' => 'parent',
                            'required' => false,
                            'validators' => array(
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function ($value, GenericValidator $validator) {
                                            $old = $this->parent;
                                            if ($value == null) {
                                                $this->parent = null;
                                            } else {
                                                if ($this->getUser() == null) {
                                                    if ($value->getUser() != null) {
                                                        $this->setUser($value->getUser());
                                                    }
                                                } else {
                                                    if ($value->getUser() == null) {
                                                        $this->parent = $value;
                                                        $value->setUser($this->getUser());
                                                    } else {
                                                        if ($value->getUser()->getId() != $this->getUser()->getId()) {
                                                            $validator->error("The parent of this category must have the some user!");
                                                            return false;
                                                        }
                                                    }
                                                }

                                                if ($this->getParent() != null
                                                        && $this->getParent()->getCode() === $value->getCode())
                                                    return true;

                                                $this->parent = $value;
                                                $this->parent->addChild($this);
                                                $this->setFlowType($this->parent->getFlowType());
                                            }

                                            if ($old != null) {
                                                $old->removeChild($this);
                                            }

                                            return true;
                                        },
                                    ),
                                ),
                            ),
                        ),
                        'child' => array(
                            'name' => 'child',
                            'required' => false,
                            'validator' => array(
                                array(
                                    'name' => 'Callback',
                                    'options' => array(
                                        'callback' => function ($value) {
                                            // some user of parent?
                                            if ($this->user != null && $value->user != null)
                                                if (($this->getUser()->getId() == $value->getUser()->getId())
                                                        && ($this->flowType == $value->flowType))
                                                    return $value;
                                                else
                                                    throw new InvalidArgumentException("Input child: {$value->getUser()->name}, invalid must be: {$this->getUser()->name}");
                                            else
                                                return $value;
                                        },
                                    ),
                                ),
                            ),
                        ),
                    )
            );
        }

        return $this->inputFilter;
    }

    function __construct($user = null, $code = null, $description = null, $flowType = null, $parent = null, $children = null) {

        if ($user != null)
            $this->setUser($user);

        if ($code != null)
            $this->setCode($code);

        if ($description != null)
            $this->setDescription($description);

        if ($flowType != null)
            $this->setFlowType($flowType);

        if ($parent != null)
            $this->setParent($parent);

        if ($children != null)
            $this->setChildren($children);
    }

    /**
     * Sets the owner User of Category
     * @param User $user
     * @return Category
     */
    public function setUser($user) {
        $this->user = $this->valid('user', $user);
        return $this;
    }

    /**
     * Sets code of Category
     * @param string $code
     * @return Category
     */
    public function setCode($code) {
        $this->code = $this->valid('code', $code);
        return $this;
    }

    /**
     * Sets description of Category
     * @param string $description
     * @return Category
     */
    public function setDescription($description) {
        $this->description = $this->valid('description', $description);
        return $this;
    }

    /**
     * Sets flow type of Category
     * @param integer $flowType
     * @return Category
     */
    public function setFlowType($flowType) {
        $this->flowType = $this->valid('flowType', $flowType);
        return $this;
    }

    /**
     * Sets Category's parent
     * @param Category $parent
     * @return Category
     */
    public function setParent($parent) {
        $this->parent = $this->valid('parent', $parent);
        return $this;
    }

    /**
     * Sets category's children
     * @param Category[] $children
     * @return Category
     */
    public function setChildren($children) {
        $this->children = array();

        foreach ($children as $child)
            $this->addChild($child);

        return $this;
    }

    /**
     * Add a new child for this entity
     * @param Category $category
     * @return Category This 
     */
    public function addChild(Category $category) {
        if (!in_array($category, $this->children)) {
            $category = $this->valid('child', $category);
            $category->setParent($this);
            $this->children[] = $category;
        }
        return $this;
    }

    /**
     * Remove a Child of entity.
     * @param Category $cat Category to be removed, or index of that Category on childrens
     * @return Category Category removed
     */
    public function removeChild(Category $cat) {
        $removed = null;

        if (in_array($cat, $this->children)) {
            $index = array_search($cat, $this->children);
            $removed = $this->children[$index];
            unset($this->children[$index]);
            $this->children = array_values($this->children);
        }

        return $removed;
    }

    /**
     * Retrieves the child of index
     * @param integer $index
     * @return Category
     */
    public function getChild($index) {
        if (isset($this->children[$index]))
            return $this->children[$index];
        else
            return null;
    }

    /**
     * Retrieves category's owner
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Retrieves category's code
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Retrieves category's
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Retrieves category's flow type
     * @return integer
     */
    public function getFlowType() {
        return $this->flowType;
    }

    /**
     * Retrieves category's parent
     * @return Category
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Retrieves the children of category
     * @return Category[]
     */
    public function getChildren() {
        return $this->children;
    }

    public function __toString() {
        return __CLASS__ . "{user: " . ($this->user == null ? null : $this->user->username) . ", code : {$this->code}}";
    }

}

?>
