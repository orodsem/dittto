<?php

namespace Dittto\SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="dittto_group")
 * @ORM\Entity(repositoryClass="Dittto\UserBundle\Entity\YGroupRepository")
 */
class Group
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
     * @ORM\ManyToOne(targetEntity="YearLevel")
     * @ORM\JoinColumn(name="year_level_id", referencedColumnName="id")
     */
    private $yearLevel;

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
     * @return YearLevel
     */
    public function getYearLevel()
    {
        return $this->yearLevel;
    }

    /**
     * @param mixed $yearLevel
     */
    public function setYearLevel($yearLevel)
    {
        $this->yearLevel = $yearLevel;
    }
}