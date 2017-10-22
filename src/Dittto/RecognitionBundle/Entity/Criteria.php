<?php

namespace Dittto\RecognitionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_criteria")
 */
class Criteria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $point;

    /**
     * @ORM\Column(type="string", unique=false)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Recognition", mappedBy="criteria")
     */
    protected $recognitions;

    public function __construct()
    {
        $this->recognitions = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param mixed $point
     */
    public function setPoint($point)
    {
        $this->point = $point;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
}