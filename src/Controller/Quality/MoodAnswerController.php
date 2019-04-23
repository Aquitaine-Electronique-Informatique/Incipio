<?php

namespace App\Controller\Quality;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quality\MoodQuestion;
use App\Entity\Quality\MoodAnswer;
use App\Form\Quality\MoodAnswerType;

class MoodAnswerController extends Controller
{

    public function newMoodAnswer(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $personne = $user->getPersonne();
        $current_question = $em->getRepository(MoodQuestion::class)->findOneBy([], ['id' => 'desc']);
        $member_answers = $em->getRepository(MoodAnswer::class)->findBy(['mood_question' => $current_question, 'member' => $personne->getMembre()], []);
        if ($current_question == NULL) 
        {
            $this->addFlash('info', "Aucune question de moral n'est en cours");
            return $this->redirectToRoute('Mgate_dashboard_homepage', []);
        } 
        if (count($member_answers) != 0) 
        {
            $this->addFlash('info', "Vous avez déjà répondu à la question de moral");
            return $this->redirectToRoute('Mgate_dashboard_homepage', []);
        }

        $mood_answer = new MoodAnswer();
        $reasons = $this->get('mood_reasons')->getReasons();
        $form = $this->createForm(MoodAnswerType::class, $mood_answer, array('reasons' => $reasons));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $mood_answer->setMember($personne->getMembre());
                $mood_answer->setMoodQuestion($current_question);
                $em->persist($mood_answer);
                $em->flush();

                $this->addFlash('success', "Votre réponse a bien été prise en compte");
                return $this->redirectToRoute('Mgate_dashboard_homepage', []);
            }
        }

        return $this->render('Quality/Default/mood_answer_form.html.twig', [
                    'form' => $form->createView(),
                ]);
    }
}
