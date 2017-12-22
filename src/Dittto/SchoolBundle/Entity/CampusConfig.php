<?php

namespace Dittto\SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campus
 *
 * @ORM\Table(name="dittto_campus_config")
 * @ORM\Entity(repositoryClass="Dittto\UserBundle\Entity\CampusConfigRepository")
 */
class CampusConfig
{
    const RECOGNITION_LEVELS = array(
        'RECOGNITION_LEVEL_SAME_SCHOOL'     => 0,
        'RECOGNITION_LEVEL_SAME_CAMPUS'     => 1,
        'RECOGNITION_LEVEL_SAME_YEAR_LEVEL' => 2,
        'RECOGNITION_LEVEL_SAME_GROUP'      => 3
    );

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * This shows who can be recognised, for example everyone, only in same class etc
     *
     * @ORM\Column(name="recognition_level", type="string", length=255)
     */
    private $recognitionLevel;

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
    public function getRecognitionLevel()
    {
        return (int)$this->recognitionLevel;
    }

    /**
     * @param mixed $recognitionLevel
     */
    public function setRecognitionLevel($recognitionLevel)
    {
        $this->recognitionLevel = $recognitionLevel;
    }

    /**
     * All recognition levels
     *
     * @return array
     */
    public function getAllRecognitionLevels()
    {
        return self::RECOGNITION_LEVELS;
    }

    /**
     * Can user recognise everyone in school
     *
     * @return bool
     */
    public function isSameSchool()
    {
        return
            $this->getRecognitionLevel() === self::RECOGNITION_LEVELS['RECOGNITION_LEVEL_SAME_SCHOOL'];
    }

    /**
     * Can user recognise everyone in the same campus
     *
     * @return bool
     */
    public function isSameCampus()
    {
        return
            $this->getRecognitionLevel() === self::RECOGNITION_LEVELS['RECOGNITION_LEVEL_SAME_CAMPUS'];
    }

    /**
     * Can user recognise everyone in the same year level
     *
     * @return bool
     */
    public function isSameYearLevel()
    {
        return
            $this->getRecognitionLevel() === self::RECOGNITION_LEVELS['RECOGNITION_LEVEL_SAME_YEAR_LEVEL'];
    }

    /**
     * Can user recognise everyone in the same class
     *
     * @return bool
     */
    public function isSameGroup()
    {
        return
            $this->getRecognitionLevel() === self::RECOGNITION_LEVELS['RECOGNITION_LEVEL_SAME_GROUP'];
    }

}