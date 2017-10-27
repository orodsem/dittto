<?php

namespace Dittto\RecognitionBundle\Entity\Repository;


use Dittto\RecognitionBundle\Entity\RecognitionReceived;
use Doctrine\ORM\EntityRepository;

class RecognitionReceivedRepository extends EntityRepository
{
    /**
     * returns total number of recognitions received by the given user
     *
     * @param $userId
     * @return int|mixed
     */
    public function getRecognitionReceivedByUserId($userId)
    {
        $totalReceivedByUser = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.receiver = :receiver')
            ->setParameter('receiver', $userId)
            ->getQuery()->getSingleScalarResult();

        // make sure it's integer
        $totalReceivedByUser = (int)$totalReceivedByUser;

        return $totalReceivedByUser;
    }

    /**
     * @param $userId
     * @return RecognitionReceived[]
     */
    public function getNotRepliedRecognitionsByUserId($userId)
    {
        $notRepliedByUser = $this->createQueryBuilder('r')
            ->where('r.receiver = :receiver')
            ->andWhere('r.hasReplied = :hasReplied')
            ->setParameters(array('receiver' => $userId, 'hasReplied' => '0'))
            ->getQuery()->getResult();

        return $notRepliedByUser;
    }
}