<?php

namespace Admin\Model\Entity;

use Admin\Model\Entity\User;
use Core\Model\Entity\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entity User
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 * 
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends Entity {

    /**
     * Unique identifier of User
     * @var int 
     * 
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id = null;

    /**
     * Username used in login
     * @var string
     * 
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     */
    protected $username = null;

    /**
     * Password of User (Cripted MD5)
     * @var string
     * 
     * @ORM\Column(type="string", length=32, unique=false, nullable=false)
     */
    protected $password = null;

    /**
     * Role gived to User
     * @var string
     * 
     * @ORM\Column(type="string", length=10, unique=false, nullable=false)
     */
    protected $role;

    /**
     * Name of User
     * @var string
     * 
     * @ORM\Column(type="string", length=60, unique=false, nullable=false)
     */
    protected $name = null;

    /**
     * E-mail of User
     * @var string
     * 
     * @ORM\Column(type="string", length=45, unique=false, nullable=false)
     */
    protected $email = null;

    /**
     * Date of User Criation
     * @var date
     * 
     * @ORM\Column(type="date", unique=false, nullable=false, name="dt_criation")
     */
    protected $dateCreation = null;

    /**
     * Flag if the User is Active
     * @var boolean
     * 
     * @ORM\Column(type="boolean", unique=false, nullable=false)
     */
    protected $active = false;

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
                        'username' => array(
                            'name' => 'username',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StringToLower'),
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
                                        'mix' => 5,
                                        'max' => 50,
                                    )
                                ),
                            ),
                        ),
                        'password' => array(
                            'name' => 'password',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'min' => 8,
                                        'max' => 32
                                    ),
                                ),
                            ),
                        ),
                        'role' => array(
                            'name' => 'role',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StripTags'),
                                array('name' => 'StringToLower'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array('name' => 'Alnum'),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'mix' => '1',
                                        'max' => '10'
                                    )
                                ),
                                array(
                                    'name' => 'InArray',
                                    'options' => array(
                                        'haystack' => array('admin', 'commun', 'guest')
                                    ),
                                ),
                            )
                        ),
                        'email' => array(
                            'name' => 'email',
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
                                        'max' => 45
                                    )
                                ),
                                array('name' => 'EmailAddress'),
                            ),
                        ),
                        'name' => array(
                            'name' => 'name',
                            'required' => true,
                            'filters' => array(
                                array('name' => 'StringTrim'),
                                array('name' => 'StripTags'),
                                array('name' => 'HtmlEntities'),
                            ),
                            'validators' => array(
                                array('name' => 'NotEmpty'),
                                array(
                                    'name' => 'StringLength',
                                    'options' => array(
                                        'min' => 1,
                                        'max' => 60
                                    )
                                ),
                            )
                        ),
                        'dateCreation' => array(
                            'name' => 'dateCreation',
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
                        'active' => array(
                            'name' => 'active',
                            'required' => true,
                            'filters' => array(
                                array(
                                    'name' => 'Boolean',
                                    'options' => array(
                                        'type' => array('all'),
                                        'casting' => true,
                                    )
                                ),
                                array('name' => 'Int'),
                            ),
                        ),
                    )
            );
        }

        return $this->inputFilter;
    }

    /**
     * Construct a new User
     * 
     * @param string $id
     * @param string $username
     * @param string $criptPassword Cripted with MD5 password
     * @param string $role
     * @param string $name
     * @param string $email
     * @param DateTime $dateCriation Criation's date of User, if null then will be use now
     * @param string $active Default its TRUE
     */
    public function __construct($id = null, $username = null, $criptPassword = null, $role = null, $name = null, $email = null, DateTime $dateCriation = null, $active = null) {
        if (!is_null($id))
            $this->setId($id);

        if (!is_null($username))
            $this->setUsername($username);

        if (!is_null($criptPassword))
            $this->setPassword($criptPassword);

        if (!is_null($role))
            $this->setRole($role);

        if (!is_null($name))
            $this->setName($name);

        if (!is_null($email))
            $this->setEmail($email);

        if (!is_null($dateCriation))
            $this->setDateCreation($dateCriation);
        else
            $this->setDateCreation(new DateTime('now'));

        if (!is_null($active))
            $this->setActive($active);
        else
            $this->setActive(true);
    }

    /**
     * Set ID
     * @param integer $id
     * @return User
     */
    public function setId($id) {
        $this->id = $this->valid('id', $id);
        return $this;
    }

    /**
     * Set username
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $this->valid('username', $username);
        return $this;
    }

    /**
     * Set password (MD5)
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $this->valid('password', $password);
        return $this;
    }

    /**
     * Set role
     * @param string $role
     * @return User
     */
    public function setRole($role) {
        $this->role = $this->valid('role', $role);
        return $this;
    }

    /**
     * Set name
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $this->valid('name', $name);
        return $this;
    }

    /**
     * set e-mail
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $this->valid('email', $email);
        return $this;
    }

    /**
     * Set date of criation
     * @param string $dateCreation
     * @return User
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $this->valid('dateCriation', $dateCreation);
        return $this;
    }

    /**
     * Set active status 
     * @param boolean $active
     * @return User
     */
    public function setActive($active) {
        $this->active = $this->valid('active', $active);
        return $this;
    }

    /**
     * Retrives ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Retrives Username
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Retrives Password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Retrives role
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Retrives name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Retrives e-mail
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Retrives the creation's date
     * @return DateTime
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Retrives the active status
     * @return boolean
     */
    public function isActive() {
        return $this->active;
    }

    public function __toString() {
        return __CLASS__ . "{id : {$this->id}, username : {$this->username}}";
    }

}

?>
