<?php

namespace ToptalTimezone\Orm;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Entity @Table(name="Users")
 **/
class User implements \JsonSerializable
{
    use UnserializableFromJson;
    use ValidatableObject;

    /**
     * @Id @Column(type="integer") @GeneratedValue 
     * @var int
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $email;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * @OneToMany(targetEntity="Timezone", mappedBy="user")
     * @var Timezones[]
     **/
    protected $timezones = null;

    public function __construct()
    {
        $this->timezones = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function addTimezone($timezone) {
        $this->timezones[] = $timezone;
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {        
        $metadata->addPropertyConstraint('name', new NotBlank(['message' => 'Name cannot be empty']));        
        $metadata->addPropertyConstraint('password', new NotBlank(['message' => 'Password cannot be empty']));
        $metadata->addPropertyConstraint('email', new Email(['message' => 'Email cannot be empty']));        
    }

    public function jsonSerialize() {
        return ['id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail()];
    }

}