<?php

namespace App\Repository\Quality;

use Doctrine\ORM\EntityRepository;
use App\Entity\Quality\ReactivityQuestion;

class ReactivityQuestionRepository extends EntityRepository
{
    public function hasPoleStats($id)
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('p');
        $query->from(ReactivityQuestion::class, 'p');
        $query->where('p.reactivity_question = :id');
        $query->setParameter('id', $id);
        return count($query->getQuery()->getResult()) > 0;
    }

}