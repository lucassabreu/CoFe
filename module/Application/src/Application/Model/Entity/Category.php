<?php

namespace Application\Model\Entity;

use Admin\Model\Entity\User;
use Application\Model\Entity\Category;
use Core\Model\Entity\Entity;
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
 * @property array $children Cagories which father its this category
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
            $this->inputFilter = $factory->createInputFilter(array(
                'number' => array(
                    'name' => 'number',
                    'filters' => array(
                        array('name' => 'Int')
                    )
                ),
            ));
        }

        return $this->inputFilter;
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
     * Set parent of category
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent) {
        $this->parent = $this->valid('parent', $parent);
        return $this;
    }

    /**
     * Set childrens of this category
     * @param ArrayCollection|array $children
     * @return Category
     */
    public function setChildren($children = array()) {
        $this->children = $children;
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
        return $this->flowType;
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
        return $this->children;
    }

    public function __toString() {
        return __CLASS__ . "{code: $this->code}";
    }

}

?>
