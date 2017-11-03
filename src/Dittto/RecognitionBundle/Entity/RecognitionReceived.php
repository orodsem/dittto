<?php

namespace Dittto\RecognitionBundle\Entity;

use AppBundle\Entity\BaseEntity;
use Dittto\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_recognition_received")
 * @ORM\Entity(repositoryClass="Dittto\RecognitionBundle\Entity\Repository\RecognitionReceivedRepository")
*/
class RecognitionReceived extends BaseEntity
{
    const NOT_RESPONDED = 0;
    const RESPONDED = 1;
    const RESPONSE = 2; // this is a response recognition. e.g. someone recognise you and you like it

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
     * @ORM\ManyToOne(targetEntity="\Dittto\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id", nullable=false)
     */
    private $receiver;

    /**
     * @ORM\Column(name="response_type", type="smallint", nullable=false, options={"default" : 0})
     */
    private $responseType;

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
        $this->responseType = self::NOT_RESPONDED;
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
     * @return Recognition
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
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * @param mixed $responseType
     */
    public function setResponseType($responseType)
    {
        $this->responseType = is_null($responseType) ? self::NOT_RESPONDED : $responseType;

        if ($this->getResponseType()) {
            $this->setRepliedAt(new \DateTime());
        }
    }

    /**
     * @return mixed
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * @return mixed
     */
    public function getRepliedAt()
    {
        return $this->repliedAt;
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