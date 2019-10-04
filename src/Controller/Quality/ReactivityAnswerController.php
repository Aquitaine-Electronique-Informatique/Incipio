<?php

namespace App\Controller\Quality;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Quality\ReactivityAnswerType;
use DateTime;
use App\Entity\Quality\ReactivityQuestion as ReactivityQuestion;
use App\Entity\Quality\ReactivityAnswer as ReactivityAnswer;

class ReactivityAnswerController extends Controller
{
    /**
     * @Route("/reactivity/answer", name="quality_reactivity_answer", methods={"GET"})
     */
    public function newReactivityAnswer(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $current_question = $em->getRepository(ReactivityQuestion::class)->findOneBy([], ['id' => 'desc']);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');   
        $user = $this->getUser();
        $personne = $user->getPersonne();
        $member_answers = $em->getRepository(ReactivityAnswer::class)->findBy(['reactivity_question' => $current_question, 'member' => $personne->getMembre()], []); 
        if ($current_question == NULL) 
        {
            $this->addFlash('info', "Aucune question de réactivité n'est en cours");
            return $this->redirectToRoute('dashboard_homepage', []);
        }
        if (count($member_answers) != 0) 
        {
            $this->addFlash('info', "Vous avez déjà répondu à la question de réactivité");
            return $this->redirectToRoute('dashboard_homepage', []);
        }
        if (date_diff($current_question->getAskedAt(), new DateTime("now"))->d >= 1 && $member_answers->count() == 0) 
        {
            $this->addFlash('danger', "Trop tard ! Vous avez été trop lent à répondre !");
            return $this->redirectToRoute('dashboard_homepage', []);
        }
        
        $reactivity_answer = new ReactivityAnswer();
        $form = $this->createForm(ReactivityAnswerType::class, $reactivity_answer);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $reactivity_answer->setMember($personne->getMembre());
                $reactivity_answer->setReactivityQuestion($current_question);
                $em->persist($reactivity_answer);
                $em->flush();
                
                $this->addFlash('success', "Votre réponse a bien été prise en compte");
                return $this->redirectToRoute('dashboard_homepage', []);
            }
        }

        return $this->render('Quality/Default/reactivity_answer_form.html.twig', [
                    'form' => $form->createView(), 'current_question' => $current_question, 'isAdmin' => false]);
    }

    /**
     * @Security("has_role('ROLE_QUALITE') || has_role('ROLE_ADMIN')")
     */
    public function validateAnswer($id)
    {
        $success_message = 'Réponse correctement validée';

        $em = $this->getDoctrine()->getManager();
        $answer = $em->getRepository(ReactivityAnswer::class)->find($id);
        $answer->setIsValidated(true);
        $em->merge($answer);
        $em->flush();

        $this->addFlash('success', $success_message);
        return $this->redirectToRoute('quality_admin_reactivity', []);
    }
}
