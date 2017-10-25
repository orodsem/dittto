<?php

namespace Dittto\UserBundle\Entity\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username AND u.isActive = :isActive')
            ->setParameter('username', $username)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}