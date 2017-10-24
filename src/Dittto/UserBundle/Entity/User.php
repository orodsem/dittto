<?php

namespace Dittto\UserBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Dittto\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="dittto_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true, options={"default" : 0})
     */
    private $isDeleted;

    /**
     * @ORM\ManyToMany(targetEntity="\Dittto\RecognitionBundle\Entity\Recognition", mappedBy="receivers")
     */
    protected $recognitions;

    public function __construct()
    {
        $this->recognitions = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param boolean $isDeleted
     */
    public function setDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecognitions()
    {
        return $this->recognitions;
    }
    /**
     * @param mixed $recognitions
     */
    public function setRecognitions($recognitions)
    {
        $this->recognitions = $recognitions;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
