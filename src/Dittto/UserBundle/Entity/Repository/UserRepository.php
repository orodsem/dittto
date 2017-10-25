<?php

namespace Dittto\UserBundle\Entity\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param string $username
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username AND u.isActive = :isActive')
            ->setParameter('username', $username)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $roles
     * @return array
     */
    public function getUserByRoles($roles)
    {
        $implodedRoles = implode(',', $roles);

        $users = $this->createQueryBuilder('u')
            ->select('u')
            ->Where('u.roles IN (:roles)')
            ->setParameter('roles', $implodedRoles)
        ->getQuery()
        ->getResult();

        return $users;
    }
}