<?php

namespace App\Repository\Quality;

use Doctrine\ORM\EntityRepository;
use App\Entity\Quality\MoodAnswer;

class MoodAnswerRepository extends EntityRepository
{

    public function getMoodAnswerListFromQuestionPole($question_id, $pole_id) {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('p')
        ->from(MoodAnswer::class, 'p')
        ->innerjoin('p.mood_question', 'mq')
        ->where('mq.id LIKE :question')
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
    
    
    public function getMoodAnswerListFromQuestion($question_id) {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('p')
        ->from(MoodAnswer::class, 'p')
        ->innerjoin('p.mood_question', 'mq')
        ->where('mq.id LIKE :question')
        ->setParameter('question', $question_id)
        ->getQuery();

        return $query->getResult();
    }
}

?>