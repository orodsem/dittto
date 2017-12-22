<?php

namespace Dittto\SchoolBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Campus
 *
 * @ORM\Table(name="dittto_campus")
 * @ORM\Entity(repositoryClass="Dittto\UserBundle\Entity\CampusRepository")
 */
class Campus
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="School")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="CampusConfig")
     * @ORM\JoinColumn(name="campus_config_id", referencedColumnName="id")
     */
    private $campusConfig;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @param mixed $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
    }

    /**
     * @return CampusConfig
     */
    public function getCampusConfig()
    {
        return $this->campusConfig;
    }

    /**
     * @param mixed $campusConfig
     */
    public function setCampusConfig($campusConfig)
    {
        $this->campusConfig = $campusConfig;
    }
}