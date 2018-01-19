<?php

namespace Dittto\RecognitionBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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

    /**
     * returns the rank of user based on how many recognition sent
     *
     * @param $userId
     * @return array
     */
    public function getUserSentRankNow($userId)
    {
        $query = 'SELECT * FROM (
                      SELECT s.*, @rank := @rank + 1 rank FROM (
                        SELECT sender_id, count(*) TotalPoints FROM dittto_recognition
                        GROUP BY sender_id
                      ) s, (SELECT @rank := 0) init
                      ORDER BY TotalPoints DESC
                    ) r
                    WHERE sender_id = ' . $userId;

        $rank = $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();

        $rankDetails = array();

        if (count($rank) != 1) {
            // users haven't recognised anyone yet
            $rankDetails['rank'] = '';
            $rankDetails['imagePath'] = 'assets/img/no_rank.png';
            $rankDetails['rankMessage'] = 'It\'s time to recognise!';

            return $rankDetails;
        }

        if ($rank[0]['rank'] == 1) {
            $rankDetails['rank'] = 1;
            $rankDetails['imagePath'] = 'assets/img/rank_1.png';
            $rankDetails['rankMessage'] = 'Well done!';
        } else if ($rank[0]['rank'] == 2) {
            $rankDetails['rank'] = 2;
            $rankDetails['imagePath'] = 'assets/img/rank_2.png';
            $rankDetails['rankMessage'] = 'Good job!';
        } else if ($rank[0]['rank'] == 3) {
            $rankDetails['rank'] = 3;
            $rankDetails['imagePath'] = 'assets/img/rank_3.png';
            $rankDetails['rankMessage'] = 'Keep it up!';
        } else if ($rank[0]['rank'] > 4 || $rank[0]['rank'] < 5) {
            $rankDetails['rank'] = $rank[0]['rank'];
            $rankDetails['rankMessage'] = 'Getting there!';
        } else {
            $rankDetails['rank'] = $rank[0]['rank'];
            $rankDetails['rankMessage'] = "It's time to recognise!";
        }

        return $rankDetails;
    }

    /**
     * returns last month rank
     *
     * @param $userId
     * @return array
     */
    public function getUserSentRankLastMonth($userId)
    {
        $lastMonthStart = date("Y-n-d", strtotime("first day of previous month")) . ' 00:00:00';
        $lastMonthEnd = date("Y-n-d", strtotime("last day of previous month")) . ' 23:59:59';

        $query = 'SELECT * FROM (
                      SELECT s.*, @rank := @rank + 1 rank FROM (
                        SELECT sender_id, count(*) TotalPoints FROM dittto_recognition
                        WHERE sent_at BETWEEN "' . $lastMonthStart . '" AND "' . $lastMonthEnd .'"
                        GROUP BY sender_id
                      ) s, (SELECT @rank := 0) init
                      ORDER BY TotalPoints DESC
                    ) r
                    WHERE sender_id = ' . $userId;

        $rank = $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();

        $rankDetails = array();

        if (count($rank) != 1) {
            // users haven't recognised anyone yet
            $rankDetails['rank'] = '';

            return $rankDetails;
        }

        $rankDetails['rank'] = $rank[0]['rank'];

        return $rankDetails;
    }

    /**
     * Compare current rank with last month
     *
     * @param $userId
     * @return array
     */
    public function getRankChangedSinceLastMonth($userId)
    {
        $rankDetailsNow = $this->getUserSentRankNow($userId);
        $rankDetailsLastMonth = $this->getUserSentRankLastMonth($userId);

        $rankNow = (int)$rankDetailsNow['rank'];
        $rankLastMonth = (int)$rankDetailsLastMonth['rank'];

        $rankChanged = $rankLastMonth - $rankNow;
        if ($rankChanged > 0) {
            $rankChangedIcon = 'glyphicon glyphicon-chevron-up text-success';
        } else if ($rankChanged < 0) {
            $rankChangedIcon = 'glyphicon glyphicon-chevron-down text-danger';
        } else {
            $rankChangedIcon = 'glyphicon glyphicon-chevron-right text-muted';
        }

        $rankChangedDetails = array();
        $rankChangedDetails['changed'] = $rankChanged;
        $rankChangedDetails['icon'] = $rankChangedIcon;

        return $rankChangedDetails;
    }
}