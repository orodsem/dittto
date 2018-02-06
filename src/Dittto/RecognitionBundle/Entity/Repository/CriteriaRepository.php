<?php

namespace Dittto\RecognitionBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class CriteriaRepository extends EntityRepository
{
    public function getVisible()
    {
        $visibleCriteria = $this->createQueryBuilder('c')
            ->where('c.visible = 1');

        return $visibleCriteria;
    }
}