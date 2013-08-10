<?php

namespace Application\Model\Entity;

use Application\Model\Entity\Category;
use Core\Model\Entity\Entity;
use Core\Validator\GenericValidator;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory;

/**
 * Class representation for table <code>moviment</code>
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * 
 * @ORM\Entity
 * @ORM\Table(name="moviment")
 * 
 * @property int $id Identifier of moviment
 * @property \Application\Model\Entity\Category $category Category of moviment
 * @property float $value Value of moviment
 * @property DateTime $dateEmission Date of moviment
 * @property string $description Short description of moviment
 * @property string $notes Long description for moviment
 */
class Moviment extends Entity {

    /**
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Application\Model\Entity\Category")
     * @ORM\JoinColumn(name="num_category", referencedColumnName="num_category"))
     */
    protected $category;

    /**
     * @ORM\Column(type="decimal", unique=false, nullable=false, name="value")
     */
    protected $value;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false, name="dt_emission")
     */
    protected $dateEmission;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $notes;

    public function getInputFilter() {
        if ($this->inputFilter === null) {
            $factory = new Factory();
            $this->inputFilter = $factory->createInputFilter(
                    array(
                        'id' => array(
                            'name' => 'id',
                            'filters' => array(
                                array('name' => 'Int')
                            )
                        ),
                        'category' => array(
                            'name' => 'category',
                            'required' => false,
                            'validators' => array(
                                array(
                                    'name' => 'Core\Validator\GenericValidator',
                                    'options' => array(
                                        'validatorFunction' => function($value, GenericValidator $validator) {
                                            if ($value instanceof Category)
                                                return true;
                                            else {
                                                $validator->error('Instance of paramenter must be a Application\Model\Entity\Category');
                                                return false;
                                            }
                                        }
                                    ),
                                ),
                            ),
                        ),
                        'value' => array(
                            'name' => 'value',
                            'required' => true,
                            'filters' => array(
                                array(
                                    'name' => 'Callback',
                                    'options' => array(
                                        'callback' => function($value) {
                                            return floatval($value);
                                        }
                                    ),
                                ),
                            ),
                        ),
                        'dateEmission' => array(
                            'name' => 'dateEmission',
                            'required' => true,
                            'filters' => array(
                                array(
                                    'name' => 'Callback',
                                    'options' => array(
                                        'callback' => function($value) {
                                            if (!($value instanceof DateTime))
                                                $value = new DateTime($value);

                                            $value->setTime(0, 0, 0);

                                            return $value;
                                        }
                                    ),
                                ),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'Date',
                                    'options' => array(
                                        'format' => 'Y-m-d'
                                    )
                                )
                            )
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
                                    'name' => 'Alnum',
                                    'options' => array(
                                        'allowWhiteSpace' => true,
                                    ),
                                ),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'mix' => 5,
                                        'max' => 50,
                                    )
                                ),
                            ),
                        ),
                        'notes' => array(
                            'name' => 'description',
                            'required' => false,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StripTags'),
                            ),
                            'notes' => array(
                                array(
                                    'name' => 'Alnum',
                                    'options' => array(
                                        'allowWhiteSpace' => true,
                                    ),
                                ),
                            ),
                        ),
                    )
            );
        }

        return $this->inputFilter;
    }

    /**
     * Construct a new Moviment
     * @param int $id
     * @param Category $category
     * @param float $value
     * @param DateTime $dateEmission Default is now
     * @param string $description
     * @param string $notes
     */
    function __construct($id = null, $category = null, $value = null, $dateEmission = null, $description = null, $notes = null) {

        if ($id !== null)
            $this->setId($id);

        if ($category !== null)
            $this->setCategory($category);

        if ($value !== null)
            $this->setValue($value);

        if ($dateEmission !== null)
            $this->setDateEmission($dateEmission);
        else
            $this->setDateEmission(new DateTime('now'));

        if ($description !== null)
            $this->setDescription($description);

        if ($notes !== null)
            $this->setNotes($notes);
    }

    public function setId($id) {
        $this->id = $this->valid('id', $id);
        return $this;
    }

    public function setCategory($category) {
        $this->category = $this->valid('category', $category);
        return $this;
    }

    public function setValue($value) {
        $this->value = $this->valid('value', $value);
        return $this;
    }

    public function setDateEmission($dateEmission) {
        $this->dateEmission = $this->valid('dateEmission', $dateEmission);
        return $this;
    }

    public function setDescription($description) {
        $this->description = $this->valid('description', $description);
        return $this;
    }

    public function setNotes($notes) {
        $this->notes = $this->valid('notes', $notes);
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getValue() {
        return $this->value;
    }

    public function getDateEmission() {
        return $this->dateEmission;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getNotes() {
        return $this->notes;
    }

}

?>
