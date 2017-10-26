<?php

namespace Dittto\RecognitionBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class RecognitionReceivedRepository extends EntityRepository
{
    public function getRecognitionReceivedByUserId($userId)
    {
        $totalSentByUser = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.receiver = :receiver')
            ->setParameter('receiver', $userId)
            ->getQuery()->getSingleScalarResult();

        // make sure it's integer
        $totalSentByUser = (int)$totalSentByUser;

        return $totalSentByUser;
    }
}