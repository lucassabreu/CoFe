<?php

namespace Admin\Model\Entity;

use Core\Model\Entity\Entity;
use DateTime;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entity User
 *
 * @author Lucas dos Santos Abreu
 */
class User extends Entity {

    /**
     * @var InputFilterInterface
     */
    protected static $_inputFilter = null;

    /**
     * Unique identifier of User
     * @var int 
     */
    protected $id = null;

    /**
     * Username used in login
     * @var string
     */
    protected $username = null;

    /**
     * Password of User (Cripted MD5)
     * @var string
     */
    protected $password = null;

    /**
     * Role gived to User
     * @var string
     */
    protected $role;

    /**
     * Name of User
     * @var string
     */
    protected $name = null;

    /**
     * E-mail of User
     * @var string
     */
    protected $email = null;

    /**
     * Date of User Criation
     * @var \DateTime
     */
    protected $dateCriation = null;

    /**
     * Flag if e-mail validaded
     * @var boolean
     */
    protected $valid = false;

    /**
     * Flag if the User is Active
     * @var boolean
     */
    protected $active = false;

    protected static function _getInputFilter() {
        if (self::$_inputFilter === null) {
            $factory = new Factory();
            self::$_inputFilter = $factory->createInputFilter(
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
                                        'max' => 20
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
                                array('name' => 'StringToUpper'),
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
                                array(
                                    'name' => 'email_address',
                                    'options' => array(
                                        'domain' => false
                                    )
                                ),
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
                        'dateCriation' => array(
                            'name' => 'dateCriation',
                            'required' => true,
                            'filters' => array(
                                array(
                                    'name' => 'Callback',
                                    'options' => array(
                                        'callback' => function ($value) {
                                            return new DateTime($value);
                                        }
                                    )
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
                        'valided' => array(
                            'name' => 'valid',
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

        return self::$_inputFilter;
    }

    public function getInputFilter() {
        return self::_getInputFilter();
    }

    public function __construct() {
        $this->dateCriation = new DateTime;
    }

}

?>
