<?php
namespace Dittto\SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_school_class")
 * @ORM\Entity(repositoryClass="Dittto\SchoolBundle\Entity\Repository\SchoolClassRepository")
 */
class SchoolClass
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="\Dittto\UserBundle\Entity\UserHasSchoolClasses", mappedBy="schoolClasses",cascade={"persist","remove"} )
     */
    protected $users;

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
}