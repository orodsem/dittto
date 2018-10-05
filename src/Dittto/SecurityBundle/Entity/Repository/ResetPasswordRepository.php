<?php

namespace Dittto\SecurityBundle\Entity\Repository;

use Dittto\SecurityBundle\Entity\ResetPassword;
use Doctrine\ORM\EntityRepository;

/**
 * Class ResetPasswordRepository
 * @package Dittto\SecurityBundle\Entity\Repository
 */
class ResetPasswordRepository extends EntityRepository
{
    /**
     * Expires all valid tokens by given identifier
     *
     * @param $identifier
     */
    public function expiresTokenByIdentifier($identifier)
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException();
        }

        $this->createQueryBuilder('r')
            ->update(ResetPassword::class, 'r')
            ->set('r.hasExpired', true)
            ->where('r.identifier = :identifier')
            ->andWhere('r.hasExpired = :hasExpired')
            ->setParameter('identifier', $identifier)
            ->setParameter('hasExpired', false)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Returns Reset Password by given token
     *
     * @param $token
     * @return null|object
     */
    public function getByToken($token)
    {
        $criteria = array(
            'token' => $token,
            'hasExpired' => false
        );

        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->load($criteria, null, null, array(), null, 1);
    }

    /**
     * Is token valid means token hasn't expired or used
     * TODO: Tokens after some time should be expired
     *
     * @param $token
     * @return bool
     */
    public function isTokenValid($token)
    {
        $totalToken = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.token = :token')
            ->andWhere('r.hasExpired = :hasExpired')
            ->setParameter('token', $token)
            ->setParameter('hasExpired', false)
            ->getQuery()->getSingleScalarResult();

        return (int)$totalToken === 1;
    }
}