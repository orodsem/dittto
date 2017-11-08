<?php

namespace Dittto\RecognitionBundle\Entity;

use AppBundle\Entity\BaseEntity;
use Dittto\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Psr\Log\InvalidArgumentException;


/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_recognition")
 * @ORM\Entity(repositoryClass="Dittto\RecognitionBundle\Entity\Repository\RecognitionRepository")
 */
class Recognition extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * User of event
     * Foreign key to user
     *
     * @ORM\ManyToOne(targetEntity="\Dittto\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", nullable=true)
     */
    private $sender;

    /**
     * @ORM\ManyToMany(targetEntity="Criteria", inversedBy="dittto_recognition")
     * @ORM\JoinTable(name="dittto_recognition_criteria")
     */
    protected $criteria;

    /**
     * @ORM\ManyToMany(targetEntity="\Dittto\UserBundle\Entity\User", inversedBy="dittto_user")
     * @ORM\JoinTable(name="dittto_recognition_receivers")
     */
    protected $receivers;

    /**
     * Each recognition can have multiple recognition received objects
     *
     * @ORM\OneToMany(targetEntity="RecognitionReceived", mappedBy="recognition", cascade={"persist", "remove"})
     */
    private $recognitionReceiveds;

    /**
     * @ORM\Column(name="sent_at", type="datetime")
     */
    protected $sentAt;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
        $this->receivers = new ArrayCollection();
        $this->recognitionReceiveds = new ArrayCollection();
        $this->sentAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return ArrayCollection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * This returns one criteria. in must cases we ONLY have one criteria per recognition, however for flexibility
     * the cardinality is M-M
     *
     * @return Criteria
     */
    public function getSingleCriteria()
    {
        $criteria = $this->criteria;
        if (count($criteria) > 1 || count($criteria) == 0) {
            throw new InvalidArgumentException('[' . count($criteria) . '] is invalid. It must be one.');
        }

        return $criteria[0];
    }

    /**
     * return all criteria titles in comma separated format
     *
     * @return string
     */
    public function getAllCriteriaTitles()
    {
        $criteria = $this->criteria;
        $titles = array();

        /**  @var Criteria $criterion */
        foreach ($criteria as $key => $criterion) {
            $titles[] = $criterion->getTitle();
        }

        return implode(', ', $titles);
    }

    /**
     * @param ArrayCollection $criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * @param mixed $receivers
     */
    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;
    }

    /**
     * @return mixed
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecognitionReceiveds()
    {
        return $this->recognitionReceiveds;
    }

    public function addRecognitionReceived(RecognitionReceived $recognitionReceived)
    {
        $this->recognitionReceiveds->add($recognitionReceived);
        $recognitionReceived->setRecognition($this);

        return $this;
    }

    /**
     * @param mixed $recognitionReceiveds
     */
    public function setRecognitionReceiveds($recognitionReceiveds)
    {
        $this->recognitionReceiveds = $recognitionReceiveds;
    }
}