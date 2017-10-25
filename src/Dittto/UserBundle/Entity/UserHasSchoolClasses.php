<?php

namespace Dittto\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="dittto_user_school_class")
*/
class UserHasSchoolClasses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist","remove"} ,inversedBy="dittto_user", fetch="LAZY" )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true)
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="\Dittto\SchoolBundle\Entity\SchoolClass", cascade={"persist","remove"} ,inversedBy="dittto_school_class", fetch="LAZY" )
     * @ORM\JoinColumn(name="school_class_id", referencedColumnName="id", nullable=true)
     */
    protected $schoolClasses;

    /**
     * @var \DateTime
     * @ORM\Column(name="registered_at", type="datetime")
     */
    protected $registeredAt;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->schoolClasses = new ArrayCollection();
        $this->registeredAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getSchoolClasses()
    {
        return $this->schoolClasses;
    }

    /**
     * @param mixed $schoolClasses
     */
    public function setSchoolClasses($schoolClasses)
    {
        $this->schoolClasses = $schoolClasses;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }
}