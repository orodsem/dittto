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

    public function findUserByFullName($searchData)
    {
        $keyword = isset($searchData['term']) ? $searchData['term'] : '';

        $users = $this->createQueryBuilder('u')
            ->where('u.firstname LIKE :keyword')
            ->orWhere('u.lastname LIKE :lastname')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->setParameter('lastname', '%' . $keyword . '%')
            ->getQuery()
            ->getResult();

        return $users;
    }
}