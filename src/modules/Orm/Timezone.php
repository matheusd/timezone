<?php

namespace ToptalTimezone\Orm;

/**
 * @Entity @Table(name="Timezones")
 **/
class Timezone implements \JsonSerializable
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
     * @ManyToOne(targetEntity="User", inversedBy="timezones")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     * @var int
     */
    protected $user;    

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

    public function getUser() {
        return $this->user;
    }

    public function assignToUser($user) {
        $user->addTimezone($this);
        $this->user = $user;
    }

    public function jsonSerialize() {
        return ['id' => $this->id,
            'userId' => $this->user->getId(),
            'name' => $this->name];
    }

}
