<?php

namespace ToptalTimezone\Orm;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Entity @Table(name="Users")
 **/
class User
{
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
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {        
        $metadata->addPropertyConstraint('name', new NotBlank(['message' => 'Name cannot be empty']));        
        $metadata->addPropertyConstraint('password', new NotBlank(['message' => 'Password cannot be empty']));
        $metadata->addPropertyConstraint('email', new Email(['message' => 'Email cannot be empty']));        
    }
        
}