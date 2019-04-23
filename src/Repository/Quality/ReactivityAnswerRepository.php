<?php

namespace App\Repository\Quality;

use Doctrine\ORM\EntityRepository;
use App\Entity\Quality\ReactivityAnswer;

class ReactivityAnswerRepository extends EntityRepository
{

    public function getReactivityAnswerListFromQuestionPole($question_id, $pole_id) {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('p')
        ->from(ReactivityAnswer::class, 'p')
        ->innerjoin('p.reactivity_question', 'rq')
        ->where('rq.id LIKE :question')
        ->setParameter('question', $question_id)
        ->innerjoin('p.member', 'me')
        ->innerjoin('me.mandats', 'ma')
        ->innerjoin('ma.poste', 'pos')
        ->innerjoin('pos.pole', 'pol')
        ->andWhere('pol.id LIKE :pole')
        ->setParameter('pole', $pole_id)
        ->getQuery();

        return $query->getResult();
    }

    public function getReactivityAnswerListFromQuestion($question_id) {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('p')
        ->from(ReactivityAnswer::class, 'p')
        ->innerjoin('p.reactivity_question', 'rq')
        ->where('rq.id LIKE :question')
        ->setParameter('question', $question_id)
        ->getQuery();

        return $query->getResult();
    }
}

?>