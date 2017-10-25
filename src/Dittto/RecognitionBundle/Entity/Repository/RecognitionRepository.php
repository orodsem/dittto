<?php

namespace Dittto\RecognitionBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class RecognitionRepository extends EntityRepository
{
    /**
     * @param $userId
     * @return int
     */
    public function getTotalRecognitionSentByUserId($userId)
    {
        $totalSentByUser = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.sender = :sender')
            ->setParameter('sender', $userId)
            ->getQuery()->getSingleScalarResult();

        // make sure it's integer
        $totalSentByUser = (int)$totalSentByUser;

        return $totalSentByUser;
    }
}