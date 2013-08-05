<?php

namespace Application\Model\Entity;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Core\Model\Entity\Entity;
use Core\Validator\GenericValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory;

/**
 * Category entity
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\Table(name="category")
 * 
 * @property int $number Identifier of category
 * @property User $user Owner of category
 * @property string $code Identifier of category
 * @property string $description Description of category
 * @property boolean $flowType Flow type of category (true == input, false == output)
 * @property Category|null $parent Parent of this category
 * @property ArrayCollection $children Cagories which father its this category
 */
class Category extends Entity {

    /**
     * @ORM\Id @ORM\Column(name="num_category", type="integer")
     * @ORM\GeneratedValue
     */
    protected $number;

    /**
     * @ORM\OneToOne(targetEntity="Admin\Model\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id"))
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=6, unique=false, nullable=false)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(name="flow_type", type="boolean", unique=false, nullable=false)
     */
    protected $flowType;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="num_parent", referencedColumnName="num_category"),
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    public function getInputFilter() {
        if ($this->inputFilter === null) {
            $factory = new Factory();
            $this->inputFilter = $factory->createInputFilter(
                    array(
                        'number' => array(
                            'name' => 'number',
                            'filters' => array(
                                array('name' => 'Int')
                            )
                        ),
                        'code' => array(
                            'name' => 'code',
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
                                        'mix' => 3,
                                        'max' => 6,
                                    )
                                ),
                            ),
                        ),
                        'description' => array(
                            'name' => 'description',
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StripTags'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'Alnum',
                                    'options' => array(
                                        'allowWhiteSpace' => true,
                                    ),
                                ),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'mix' => 3,
                                        'max' => 100,
                                    )
                                ),
                            ),
                        ),
                        'flowType' => array(
                            'name' => 'flowType',
                            'filters' => array(
                                array('name' => 'Int'),
                            ),
                            'validators' => array(
                                array(
                                    'name' => 'InArray',
                                    'options' => array(
                                        'haystack' => array(0, 1),
                                    ),
                                ),
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function($value, GenericValidator $validator) {
                                            if ($this->getParent() && $value != $this->getParent()->getFlowType()) {
                                                $validator->error(sprintf("The flow type can't be changed, when category has parent"));
                                                return false;
                                            }

                                            return true;
                                        }
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
                                        'validatorFunction' => function($value, GenericValidator $validator) {
                                            if (is_null($value)) {
                                                if (!is_null($this->getParent())) {
                                                    $parent = $this->getParent();
                                                    /* @var $parent Category */

                                                    if ($parent->hasChild($this)) {
                                                        $parent->removeChild($this);
                                                    }
                                                }
                                            } else {
                                                if (!Category::validParent($this, $value, $validator))
                                                    return false;

                                                $this->parent = $value;

                                                if (!$value->hasChild($this))
                                                    $value->addChild($this);
                                            }

                                            return true;
                                        }
                                    ),
                                ),
                            ),
                        ),
                        'child' => array(
                            'name' => 'child',
                            'validators' => array(
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function($value, GenericValidator $validator) {
                                            if (!Category::validParent($value, $this, $validator))
                                                return false;

                                            $this->children->add($value);
                                            $value->parent = $value;
                                            return true;
                                        }
                                    ),
                                ),
                            ),
                        ),
                        'user' => array(
                            'name' => 'user',
                            'validators' => array(
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function($value, GenericValidator $validator) {
                                            if ($this->getUser() !== null && $this->getUser()->getId() !== $value->getId()) {
                                                $validator->error("User can't be changed");
                                                return false;
                                            }
                                            else
                                                return true;
                                        }
                                    ),
                                ),
                            ),
                        )
                    )
            );
        }

        return $this->inputFilter;
    }

    /**
     * Realize validation on child-parent relation
     * @param Category $child
     * @param Category $parent
     * @param GenericValidator $validator
     * @return boolean
     */
    protected static function validParent(Category $child, Category $parent, GenericValidator $validator) {
        if (!($parent instanceof Category)) {
            $validator->error('The parent of Category must be a object of Category.');
            return false;
        }

        if ($child->getParent() === $parent)
            return true;

        /* @var $parent Category */
        if ($parent->getNumber() === $child->getNumber()) {
            $validator->error("This category can't be its self parent.");
            return false;
        }

        if ($child->getUser() !== null) {
            if ($parent->getUser()->getId() !== $child->getUser()->getId()) {
                $validator->error(sprintf("The category %s can't be related as parent of %s.", $parent->getDescription(), $child->getDescription()));
                return false;
            }
        }
        else
            $child->setUser($parent->getUser());

        if ($parent->getFlowType() !== $child->getFlowType()) {
            $validator->error(sprintf("The parent of category %s must has the some flow type.", $child->getCode()));
            return false;
        }

        $gp = $parent->getParent();

        while (!is_null($gp)) {
            if ($gp->getNumber() === $child->getNumber()) {
                $validator->error(sprintf("The category %s can't be parent of %s, because %s is below %s.", $parent->getDescription(), $child->getDescription()));
                return false;
            }
            $gp = $gp->getParent();
        }

        return true;
    }

    /**
     * Construct a new Category
     * @param int $number 
     * @param User $user
     * @param string $code
     * @param string $description
     * @param boolean $flowType Default is TRUE
     * @param Category $parent
     * @param array $children Children of this category
     */
    public function __construct($number = null, User $user = null, $code = null, $description = null, $flowType = null, Category $parent = null, $children = array()) {
        if ($number !== null)
            $this->setNumber($number);

        if ($user !== null)
            $this->setUser($user);

        if ($code !== null)
            $this->setCode($code);

        if ($description !== null)
            $this->setDescription($description);

        if ($flowType !== null)
            $this->setFlowType($flowType);
        else
            $this->setFlowType(true);

        if ($parent !== null)
            $this->setParent($parent);

        if ($children !== null) {
            if ($children instanceof ArrayCollection)
                $this->setChildren($children);
            else
                $this->setChildren(new ArrayCollection($children));
        } else {
            $this->setChildren(new ArrayCollection());
        }
    }

    /**
     * Set parent of category
     * @param Category $parent
     * @return Category
     */
    public function setParent($parent) {
        if (!is_null($parent) && !($parent instanceof Category))
            throw InvalidArgumentException("Value must be NULL or instance of " . __CLASS__);
        
        $this->parent = $this->valid('parent', $parent);
        return $this;
    }

    /**
     * Add a new child for this category
     * @param Category $child
     * @return Category this
     */
    public function addChild(Category $child) {
        $this->children->add($this->valid('child', $child));
        return $this;
    }

    /**
     * Retrieves if param category is child at this category
     * @param Category $child
     * @return boolean
     */
    public function hasChild(Category $child) {
        return $this->children->contains($child);
    }

    /**
     * Remove a child from this category
     * @param Category $child
     * @return Category
     */
    public function removeChild(Category $child) {
        if ($this->hasChild($child)) {
            $this->children->removeElement($child);
            $child->setParent(null);
        }

        return $this;
    }

    /**
     * Set childrens of this category
     * @param ArrayCollection|array $children
     * @return Category
     */
    public function setChildren($children = array()) {
        foreach ($children as $child)
            $this->addChild($child);

        return $this;
    }

    /**
     * Sets the identifier number of this category
     * @param integer $number
     * @return Category
     */
    public function setNumber($number) {
        $this->number = $this->valid('number', $number);
        return $this;
    }

    /**
     * Set the owner of entity
     * @param User $user
     * @return Category
     */
    public function setUser(User $user) {
        $this->user = $this->valid('user', $user);
        return $this;
    }

    /**
     * Set code identifier of category
     * @param string $code
     * @return Category
     */
    public function setCode($code) {
        $this->code = $this->valid('code', $code);
        return $this;
    }

    /**
     * Set the description of category
     * @param string $description
     * @return Category
     */
    public function setDescription($description) {
        $this->description = $this->valid('description', $description);
        return $this;
    }

    /**
     * Set if category is input (true) or output (false)
     * @param boolean $flowType
     * @return Category
     */
    public function setFlowType($flowType) {
        $this->flowType = $this->valid('flowType', $flowType);
        return $this;
    }

    /**
     * Retrieves the identifier number of this category
     * @return integer
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Retrieves the owner 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Retrieves the code of category
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Retrieves the description of category
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Retrieves the flow type of category
     * @return boolean
     */
    public function getFlowType() {
        return (int) ($this->flowType);
    }

    /**
     * Retrieves if category is input flow type
     * @return boolean
     */
    public function isInput() {
        return $this->flowType;
    }

    /**
     * Retrieves if category is output flow type
     * @return boolean
     */
    public function isOutput() {
        return !$this->flowType;
    }

    /**
     * Retrieves the parent of category
     * @return Category
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Retrieves the children of this category
     * @return ArrayCollection|array
     */
    public function getChildren() {
        if (!is_null($this->children))
            return $this->children->toArray();
        else
            return array();
    }

    public function __toString() {
        return $this->code;
    }

}

?>
