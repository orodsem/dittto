<?php

namespace Dittto\RecognitionBundle\Entity;

use Dittto\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_recognition_received")
 * @ORM\Entity(repositoryClass="Dittto\RecognitionBundle\Entity\Repository\RecognitionReceivedRepository")
*/
class RecognitionReceived
{
    const NOT_REPLIED = 0;
    const REPLIED = 1;
    const NOT_REQUIERED = -1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * recognition received including receiver details
     * Foreign key to recognition
     *
     * @ORM\ManyToOne(targetEntity="Recognition", inversedBy="recognitionReceiveds", cascade={"persist"})
     * @ORM\JoinColumn(name="recognition_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $recognition;

    /**
     * @ORM\OneToOne(targetEntity="\Dittto\UserBundle\Entity\User",cascade={"persist"})
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id", nullable=true)
     **/
    private $receiver;

    /**
     * @ORM\Column(name="has_replied", type="boolean", nullable=true, options={"default" : 0})
     */
    private $hasReplied;

    /**
     * @ORM\Column(name="received_at", type="datetime")
     */
    protected $receivedAt;

    /**
     * @ORM\Column(name="replied_at", type="datetime", nullable=true)
     */
    protected $repliedAt;

    public function __construct()
    {
        $this->hasReplied = self::NOT_REPLIED;
        $this->receivedAt = new \DateTime();
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
    public function getRecognition()
    {
        return $this->recognition;
    }

    /**
     * @param mixed $recognition
     */
    public function setRecognition(Recognition $recognition)
    {
        $this->recognition = $recognition;
    }

    /**
     * @return mixed
     */
    public function hasReplied()
    {
        return $this->hasReplied;
    }

    /**
     * @param mixed $hasReplied
     */
    public function setReplied($hasReplied)
    {
        $this->hasReplied = is_null($hasReplied) ? self::NOT_REPLIED : $hasReplied;
    }

    /**
     * @return mixed
     */
    public function getReceivedAt()
    {
        $receivedAt = date('d/m/Y', $this->receivedAt);
        return $receivedAt;
    }

    /**
     * @return mixed
     */
    public function getRepliedAt()
    {
        $repliedAt = date('d/m/Y', $this->repliedAt);
        return $repliedAt;
    }

    /**
     * @param \DateTime $repliedAt
     */
    public function setRepliedAt(\DateTime $repliedAt)
    {
        $this->repliedAt = $repliedAt;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;
    }
}