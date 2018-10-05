<?php

namespace Dittto\SecurityBundle\Entity;

use AppBundle\Entity\BaseEntity;
use AppBundle\Service\Security\Encryption;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dittto_reset_password")
 * @ORM\Entity(repositoryClass="Dittto\SecurityBundle\Entity\Repository\ResetPasswordRepository")
 */
class ResetPassword extends BaseEntity
{
    const EXPIRATION_TYPE_USED = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $encryptedIdentifier;

    /**
     * @ORM\Column(name="request_at", type="datetime")
     */
    protected $requestAt;

    /**
     * @ORM\Column(name="has_expired", type="boolean", nullable=true, options={"default" : 0})
     */
    private $hasExpired;

    /**
     * @ORM\Column(name="expired_at", type="datetime", nullable=true)
     */
    protected $expiredAt;

    /**
     * @ORM\Column(name="expiration_type", type="string", length=10, nullable=true)
     */
    protected $expirationType;


    public function __construct()
    {
        $this->requestAt = new \DateTime();
        $this->hasExpired = false;
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
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function setToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getRequestAt()
    {
        return $this->requestAt;
    }

    /**
     * @param mixed $requestAt
     */
    public function setRequestAt($requestAt)
    {
        $this->requestAt = $requestAt;
    }

    /**
     * @return mixed
     */
    public function getHasExpired()
    {
        return $this->hasExpired;
    }

    /**
     * @param mixed $hasExpired
     */
    public function setHasExpired($hasExpired)
    {
        $this->hasExpired = $hasExpired;
    }

    /**
     * @return mixed
     */
    public function getEncryptedIdentifier()
    {
        return $this->encryptedIdentifier;
    }

    /**
     * @param mixed $encryptedIdentifier
     */
    private function setEncryptedIdentifier($encryptedIdentifier)
    {
        $this->encryptedIdentifier = $encryptedIdentifier;
    }

    /**
     * @return mixed
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param mixed $expiredAt
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * @return mixed
     */
    public function getExpirationType()
    {
        return $this->expirationType;
    }

    /**
     * @param mixed $expirationType
     */
    public function setExpirationType($expirationType)
    {
        $this->expirationType = $expirationType;
    }

    /**
     * @param Encryption $encryptionService
     */
    public function setEncryptIdentifier(Encryption $encryptionService)
    {
       $this->setEncryptedIdentifier($encryptionService->encrypt($this->getIdentifier()));
    }

    /**
     * generate a random token
     */
    public function generateToken()
    {
        // generate a random token
        $token = bin2hex(random_bytes(50));
        // make sure it's not bigger than DB column
        $token = strlen($token) > 200 ? substr($token, 0, 200) : $token;

        $this->token = $token;
    }

    /**
     * Expires the token
     */
    public function expiresToken()
    {
        $this->setHasExpired(true);
        $this->setExpiredAt(new \DateTime());
        $this->setExpirationType(self::EXPIRATION_TYPE_USED);

        return $this;
    }
}