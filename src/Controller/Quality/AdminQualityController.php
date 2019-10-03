<?php

namespace App\Controller\Quality;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quality\ReactivityQuestion;
use App\Entity\Quality\ReactivityAnswer;
use App\Entity\Quality\MoodQuestion;
use App\Entity\Quality\MoodAnswer;
use App\Entity\Quality\PoleStat;
use App\Entity\Quality\MoodStat;
use App\Form\Quality\ReactivityQuestionType;
use App\Entity\Personne\Pole;
use App\Entity\Personne\Membre;
use DateInterval;

class AdminQualityController extends Controller
{
    /**
     * @Security("has_role('ROLE_QUALITE')")
     * @Route("/reactivity/admin", name="quality_reactivity_admin", methods={"GET"})
     */
    public function reactivityIndex()
    {
        $em = $this->getDoctrine()->getManager();
        $current_question = $em->getRepository(ReactivityQuestion::class)->findOneBy([], ['id' => 'desc']);
        $questions = $this->processReactivityStats($em->getRepository(ReactivityQuestion::class)->findAll([], ['date' => 'asc']));
        $pole_names = $this->processPoleNames();
        $isAdmin = true;
        $canGenerate = false;
        $questionOngoing = false;
        $answers = [];
        if ($current_question != null) {
            $answers = $em->getRepository(ReactivityAnswer::class)->getReactivityAnswerListFromQuestion($current_question->getId());
            if ($current_question->isFinished()) {
                if (!$em->getRepository(ReactivityQuestion::class)->hasPoleStats($current_question->getId()))
                    $canGenerate = true;
            }
            else {
                $questionOngoing = true;
            }
        }
        return $this->render('Quality/Default/reactivity_index.html.twig', 
        ['current_question' => $current_question, 'answers' => $answers, 'questions' => $questions, 'isAdmin' => $isAdmin, 'canGenerate' => $canGenerate, 'displayQuestionForm' => !$questionOngoing, 'pole_names' => $pole_names]);
    }

    /**
     * @Security("has_role('ROLE_QUALITE')")
     * @Route("/mood/admin", name="quality_mood_admin", methods={"GET"})
     */
    public function moodIndex()
    {
        $em = $this->getDoctrine()->getManager();
        $current_question = $em->getRepository(MoodQuestion::class)->findOneBy([], ['id' => 'desc']);
        $questions = $this->processMoodStats($em->getRepository(MoodQuestion::class)->findAll([], ['date' => 'asc']));
        $pole_names = $this->processPoleNames();
        $mood_reasons = $this->get('mood_reasons')->getReasons();
        $isAdmin = true;
        $canGenerate = false;
        $questionOngoing = false;
        if ($current_question != null) {
            $answers = $em->getRepository(MoodAnswer::class)->getMoodAnswerListFromQuestion($current_question->getId());
            if ($current_question->isFinished()) {
                if (!$current_question->getHasMoodStats())
                    $canGenerate = true;
            }
            else {
                $questionOngoing = true;
            }
        }
        else {
            $answers = NULL;
        }
        return $this->render('Quality/Default/mood_index.html.twig', 
        ['current_question' => $current_question, 'answers' => $answers, 'questions' => $questions, 'isAdmin' => $isAdmin, 'canGenerate' => $canGenerate, 'displayQuestionForm' => !$questionOngoing, 'pole_names' => $pole_names, 'mood_reasons' => $mood_reasons]);
    }

    public function processPoleNames() 
    {
        $em = $this->getDoctrine()->getManager();
        $pole_names = $em->createQueryBuilder()->select('p.nom')->from(Pole::class, 'p')->orderBy('p.nom', 'ASC')
        ->getQuery()->getResult();
        foreach ($pole_names as $key => $pole_name) {
            $pole_names[$key] = "'".$pole_name['nom']."'";
        }
        return $pole_names;
    }

    public function processReactivityStats($questions)
    {
        foreach ($questions as $key => $question) {
            $results = array();
            $stats = $question->getPolestats();
            foreach ($stats as $stat) {
                $results[0][] = $stat->getGoodAnswer();
                $results[1][] = $stat->getBadAnswer();
                $results[2][] = $stat->getNoAnswer();
            }
            $question->results = $results;
        }
        return $questions; 
    }

    public function processMoodStats($questions)
    {
        foreach ($questions as $key => $question) {
            $results = array();
            $attendency = array();
            $stats = $question->getMoodStats();
            foreach ($stats as $stat) {
                $results[0][] = $stat->getMediumMood();
                $attendency[0][] = $stat->getAnswer();
                $attendency[1][] = $stat->getNoAnswer();
            }
            $question->results = $results;
            $question->attendency = $attendency;
        }
        return $questions; 
    }

    /**
     * @Security("has_role('ROLE_QUALITE')")
     */
    public function newReactivityQuestion(Request $request)
    {
        $success_message = 'Votre question pour le test de réactivité a été envoyée';
        $em = $this->getDoctrine()->getManager();
        $reactivity_question = new ReactivityQuestion();

        $form = $this->createForm(ReactivityQuestionType::class, $reactivity_question);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($reactivity_question);
                $em->flush();

                $members = $em->getRepository(Membre::class)->getMembresAdminCourant();
                $bcc = [];
                $members_count = count($members);
                $i = 0;
                foreach ($members as $key => $member) {
		    if ($i == 0)
                        $to = $member->getEmailEMSE();
                    else 
                        $bcc[] = $member->getEmailEMSE();
                    $i++;    
                }

                $message = (new \Swift_Message('[Test de Réactivité] Nouveau test'))
                    ->setFrom('robot@junior-aei.com')
                    ->setTo($to)
                    ->setBcc($bcc)
                    ->setBody(
                        $this->renderView(
                        'Quality/Emails/reactivity_question_mail.html.twig',
                        array('question' => $reactivity_question->getText())
                    ),
                    'text/html'
                );
                
                $this->get('mailer')->send($message);

                $this->addFlash('success', $success_message);
            }
        }

        return $this->render('Quality/Default/new_question.html.twig', [
                    'form' => $form->createView(),
                ]);
    }

    /**
     * @Security("has_role('ROLE_QUALITE')")
     */
    public function newMoodQuestion() 
    {
        $success_message = 'Votre question pour le test de réactivité a été envoyée';
        $em = $this->getDoctrine()->getManager();
        $mood_question = new MoodQuestion();
        $em->persist($mood_question);
        $em->flush();

        $members = $em->getRepository(Membre::class)->getMembresAdminCourant();
        $bcc = [];
        $members_count = count($members);
        $i = 0;
        foreach ($members as $key => $member) {
            if ($i == 0)
                $to = $member->getEmailEMSE();
            else 
                $bcc[] = $member->getEmailEMSE();   
            $i++; 
        }

        $message = (new \Swift_Message('[Test de Moral] Nouveau test'))
            ->setFrom('robot@junior-aei.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                'Quality/Emails/mood_question_mail.html.twig'
            ),
            'text/html'
        );
        
        $this->get('mailer')->send($message);
        
        $this->addFlash('success', $success_message);
        return $this->redirectToRoute('AEI_quality_admin_mood', []);

    }

    /**
     * @Security("has_role('ROLE_QUALITE')")
     */
    public function generateReactivityStats($id)
    {
        $success_message = 'Les statistiques de réactivité de ce mois ont bien été générées';

        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository(ReactivityQuestion::class)->find($id);
        $question_expiration = $question->getAskedAt()->add(DateInterval::createfromdatestring('+1 day'));
        $current_prom = $em->getRepository(Membre::class)->getLastPromo();

        if (!$question->getHasPolestats()) {
            $poles = $em->getRepository(Pole::class)->findAll();
            foreach ($poles as $pole) {
                $answers = $em->getRepository(ReactivityAnswer::class)->getReactivityAnswerListFromQuestionPole($question->getId(), $pole->getId());
                $members_nb = count($em->getRepository(Membre::class)->getMembresPoleCourant($pole->getId()));
                $answers_count = count($answers);
                $no_answer = 1.0 * ($members_nb - $answers_count)/$members_nb;
                $bad_answer = 0.;
                $good_answer = 0.;
                foreach($answers as $answer) {
                    if ($answer->getAnsweredAt() > $question_expiration) {
                        $no_answer += 1/$members_nb;
                    } else {
                        if ($answer->getIsValidated()) {
                            $good_answer += 1/$members_nb;
                        } else {
                            $bad_answer += 1/$members_nb;
                        }
                    }
                }
                $month = intval($question->getAskedAt()->format('m'));

                $this->createPoleStat($current_prom, $month, $question, $pole, $no_answer * 100, $bad_answer * 100, $good_answer * 100);
            }
            $this->addFlash('success', $success_message);        
        }
        return $this->redirectToRoute('AEI_quality_admin_reactivity', []);
    }

    /**
     * @Security("has_role('ROLE_QUALITE')")
     */
    public function generateMoodStats($id)
    {
        $success_message = 'Les statistiques de moral de ce mois ont bien été générées';

        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository(MoodQuestion::class)->find($id);
        $question_expiration = $question->getAskedAt()->add(DateInterval::createfromdatestring('+1 day'));
        $current_prom = $em->getRepository(Membre::class)->getLastPromo();

        if (!$question->getHasMoodStats()) {
            $poles = $em->getRepository(Pole::class)->findAll();
            foreach ($poles as $pole) {
                $answers = $em->getRepository(MoodAnswer::class)->getMoodAnswerListFromQuestionPole($question->getId(), $pole->getId());
                $members_nb = count($em->getRepository(Membre::class)->getMembresPoleCourant($pole->getId()));
                $answers_count = count($answers);
                $no_answer = 1.0 * ($members_nb - $answers_count)/$members_nb;
                $good_answer = 1.0 * $answers_count/$members_nb;
                $medium_mood = 0;
                foreach($answers as $answer) {
                    $medium_mood += $answer->getAnswer1();
                }
                $medium_mood = 1.0 * $medium_mood/$answers_count;
                $month = intval($question->getAskedAt()->format('m'));

                $this->createMoodStat($current_prom, $month, $question, $pole, $no_answer * 100, $medium_mood * 10, $good_answer * 100);
            }
            $this->addFlash('success', $success_message);        
        }
        return $this->redirectToRoute('AEI_quality_admin_mood', []);
    }

    public function createPoleStat($current_prom, $month, $question, $pole, $no_answer, $bad_answer, $good_answer) 
    {
        $em = $this->getDoctrine()->getManager();
        $polestat = new PoleStat();

        $polestat->setPromotion($current_prom);
        $polestat->setMonth($month);
        $polestat->setReactivityQuestion($question);
        $polestat->setPole($pole);
        $polestat->setNoAnswer($no_answer);
        $polestat->setBadAnswer($bad_answer);
        $polestat->setGoodAnswer($good_answer);

        $em->persist($polestat);
        $em->flush();

    }

    public function createMoodStat($current_prom, $month, $question, $pole, $no_answer, $medium_mood, $answer) 
    {
        $em = $this->getDoctrine()->getManager();
        $polestat = new MoodStat();

        $polestat->setPromotion($current_prom);
        $polestat->setMonth($month);
        $polestat->setMoodQuestion($question);
        $polestat->setPole($pole);
        $polestat->setNoAnswer($no_answer);
        $polestat->setMediumMood($medium_mood);
        $polestat->setAnswer($answer);

        $em->persist($polestat);
        $em->flush();

    }



}
