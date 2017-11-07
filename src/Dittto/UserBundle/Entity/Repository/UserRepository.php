<?php

namespace Dittto\UserBundle\Entity\Repository;

use Dittto\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEligibleRecognitionUsers(User $user)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username != :username')
            ->setParameter('username', $user->getUsername());
    }

    /**
     * @param $role
     * @return array
     */
    public function getUsersByRole($role)
    {
        $users = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%')
        ->getQuery()
        ->getResult();

        return $users;
    }
}