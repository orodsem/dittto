<?php

namespace Dittto\UserBundle\Entity\Repository;

use Dittto\SchoolBundle\Entity\Campus;
use Dittto\SchoolBundle\Entity\Group;
use Dittto\SchoolBundle\Entity\YearLevel;
use Dittto\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEligibleRecognitionUsers(User $user, $recognitionLevel = true)
    {
        // user can't recognise himself
        $whereClause = array('u.username != :username');

        $group = $user->getGroup();
        $yearLevel = $group->getYearLevel();
        $campus = $yearLevel->getCampus();

        $campusConfig = $campus->getCampusConfig();

        if ($campusConfig->isSameGroup()) {
            return $this->getUserByGroup($user, $group);
        }

        if ($campusConfig->isSameYearLevel()) {
            return $this->getUserByYearLevel($user, $yearLevel);
        }

        if ($campusConfig->isSameCampus()) {
            return $this->getUserByCampus($user, $campus);
        }

        $parameters = array(
            'username' => $user->getUsername(),
            'group' => $user->getGroup()
        );

        $recognisableUsers = $this->createQueryBuilder('u')
            ->where(implode(' AND ', $whereClause))
            ->setParameters($parameters);

        return $recognisableUsers;
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

    /**
     * @param User $user
     * @param Group $group
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUserByGroup(User $user, Group $group)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->select('u')
            ->join('DitttoSchoolBundle:Group', 'g')
            ->where('u.username != :username')
            ->andWhere('u.group = :group')
            ->setParameter('group', $group)
            ->setParameter('username', $user->getUsername())
        ;

        return $qb;
    }

    /**
     * @param User $user
     * @param YearLevel $yearLevel
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUserByYearLevel(User $user, YearLevel $yearLevel)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->select('u')
            ->innerJoin('u.group', 'g')
            ->innerJoin('g.yearLevel', 'y')
            ->where('u.username != :username')
            ->andWhere('y = :yearLevel')
            ->setParameter('yearLevel', $yearLevel)
            ->setParameter('username', $user->getUsername())
        ;

        return $qb;
    }

    /**
     * @param User $user
     * @param Campus $campus
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUserByCampus(User $user, Campus $campus)
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->select('u')
            ->innerJoin('u.group', 'g')
            ->innerJoin('g.yearLevel', 'y')
            ->innerJoin('y.campus', 'c')
            ->where('u.username != :username')
            ->andWhere('c = :campus')
            ->setParameter('campus', $campus)
            ->setParameter('username', $user->getUsername())
        ;

        return $qb;
    }

}