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
     * returns total number of recognitions received by the given user
     *
     * @param $userId
     * @return int|mixed
     */
    public function getRecognitionReceivedListByUserId($userId, $offset = 0, $limit = 5)
    {
        
        $totalReceivedByUser = $this->createQueryBuilder('r')            
            ->select('r')
            ->where('r.receiver = :receiver')            
            ->setParameter('receiver', $userId)
            ->orderBy('r.id', 'DESC')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->getQuery()->getResult();

        return $totalReceivedByUser;
    }

    /**
     * Returns all recognitions that not responded yet and should be responded
     *
     * @param $userId
     * @return RecognitionReceived[]
     */
    public function getNewRecognitionsByUserId($userId)
    {
        $notRepliedByUser = $this->createQueryBuilder('r')
            ->where('r.receiver = :receiver')
            ->andWhere('r.responseType != :responseType')
            ->setParameters(array('receiver' => $userId, 'responseType' => RecognitionReceived::RESPONDED))
            ->orderBy('r.id', 'DESC')
            ->getQuery()->getResult();

        return $notRepliedByUser;
    }
}