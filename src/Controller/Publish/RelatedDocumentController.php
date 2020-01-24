<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Henry Lucas
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Publish;

use App\Entity\Comment\Thread;
use App\Entity\Formation\Formation;
use App\Entity\Personne\Membre;
use App\Entity\Project\Etude;
use App\Entity\Publish\Document;
use App\Entity\Publish\RelatedDocument;
use App\Form\Publish\DocumentType;
use App\Service\Project\EtudePermissionChecker;
use App\Service\Publish\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RelatedDocumentController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_QUALITE')")
     * @Route(name="quality_mark_reviewed", path="/Documents/Reviews/{id}", methods={"GET","HEAD","POST"})
     *
     */
    public function review(RelatedDocument $relation)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $relation->setStatus(1);
        $em->flush();
        $this->addFlash('success', 'Document relu');
        return $this->redirectToRoute('document_relecture_list');
    }

    /**
     * @Security("has_role('ROLE_QUALITE') || has_role('ROLE_SUIVEUR')")
     * @Route(name="quality_mark_validated", path="/Documents/Validate/{id}", methods={"GET","HEAD","POST"})
     *
     */
    public function validate(RelatedDocument $relation)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $relation->setStatus(2);
        $em->flush();
        $this->addFlash('success', 'Document validé');
        return $this->redirectToRoute('document_relecture_list');
    }
}
