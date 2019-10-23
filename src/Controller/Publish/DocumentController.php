<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Publish;

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

class DocumentController extends AbstractController
{
    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_documenttype_index", path="/Documents/", methods={"GET","HEAD"})
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository(Document::class)->findAll();

        $totalSize = 0;
        foreach ($entities as $entity) {
            $totalSize += $entity->getSize();
        }

        return $this->render('Publish/Document/index.html.twig', [
            'docs' => $entities,
            'totalSize' => $totalSize
        ]);
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_voir", path="/Documents/show/{id}", methods={"GET","HEAD"})
     *
     *  @param int $id
     *
     */
    public function voir(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $doc = $em->getRepository(Document::class)->find($id);

        return $this->render('Publish/Document/voir.html.twig', [
            'doc' => $doc
        ]);
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_download", path="/Documents/download/{id}", methods={"GET","HEAD"})
     *
     * @param Document        $documentType (ParamConverter) The document to be downloaded
     * @param KernelInterface $kernel
     *
     * @return BinaryFileResponse
     *
     * @throws \Exception
     */
    public function download(Document $documentType, KernelInterface $kernel)
    {
        $documentStoragePath =
            $kernel->getProjectDir() . '' . Document::DOCUMENT_STORAGE_ROOT;
        if (
            file_exists($documentStoragePath . '/' . $documentType->getPath())
        ) {
            $response = new BinaryFileResponse(
                $documentStoragePath . '/' . $documentType->getPath()
            );
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT
            );

            return $response;
        } else {
            throw new \Exception(
                $documentStoragePath .
                    '/' .
                    $documentType->getPath() .
                    ' n\'existe pas'
            );
        }
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_uploadEtude", path="/Documents/Upload/Etude/{nom}", methods={"GET","HEAD","POST"})
     *
     * @param Request                $request
     * @param Etude                  $etude
     * @param EtudePermissionChecker $permChecker
     * @param DocumentManager        $documentManager
     * @param KernelInterface        $kernel
     *
     * @return Response
     */
    public function uploadEtude(
        Request $request,
        Etude $etude,
        EtudePermissionChecker $permChecker,
        DocumentManager $documentManager,
        KernelInterface $kernel
    ) {
        if ($permChecker->confidentielRefus($etude, $this->getUser())) {
            throw new AccessDeniedException('Cette étude est confidentielle !');
        }

        if (
            !($response = $this->upload(
                $request,
                false,
                ['etude' => $etude],
                $documentManager,
                $kernel
            ))
        ) {
            $this->addFlash('success', 'Document mis en ligne');

            return $this->redirectToRoute('project_etude_voir', [
                'nom' => $etude->getNom()
            ]);
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_reuploadEtude", path="/Documents/Reupload/Etude/{id}", methods={"GET","HEAD","POST"})
     *
     * @param Request                $request
     * @param Etude                  $etude
     * @param EtudePermissionChecker $permChecker
     * @param DocumentManager        $documentManager
     * @param KernelInterface        $kernel
     *
     * @return Response
     */
    public function reuploadEtude(
        Request $request,
        Document $doc,
        EtudePermissionChecker $permChecker,
        DocumentManager $documentManager,
        KernelInterface $kernel
    ) {
        $etude = $doc->getRelation()->getEtude();

        if ($permChecker->confidentielRefus($etude, $this->getUser())) {
            throw new AccessDeniedException('Cette étude est confidentielle !');
        }

        if (
            !($response = $this->reupload(
                $request,
                false,
                ['etude' => $etude],
                $documentManager,
                $kernel,
                $doc
            ))
        ) {
            $this->addFlash('success', 'Document mis en ligne');

            return $this->redirectToRoute('project_etude_voir', [
                'nom' => $etude->getNom()
            ]);
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_uploadEtudiant", path="/Documents/Upload/Etudiant/{id}", methods={"GET","HEAD","POST"})
     *
     * @param Request         $request
     * @param Membre          $membre
     * @param DocumentManager $documentManager
     * @param KernelInterface $kernel
     *
     * @return bool|RedirectResponse|Response
     */
    public function uploadEtudiant(
        Request $request,
        Membre $membre,
        DocumentManager $documentManager,
        KernelInterface $kernel
    ) {
        $options['etudiant'] = $membre;

        if (
            !($response = $this->upload(
                $request,
                false,
                $options,
                $documentManager,
                $kernel
            ))
        ) {
            $this->addFlash('success', 'Document mis en ligne');

            return $this->redirectToRoute('personne_membre_voir', [
                'id' => $membre->getId()
            ]);
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_CA')")
     * @Route(name="publish_document_uploadFormation", path="/Documents/Upload/Formation/{id}", methods={"GET","HEAD"})
     *
     * @param Formation $formation
     *
     * @return JsonResponse
     */
    public function uploadFormation(Formation $formation)
    {
        return new JsonResponse([], Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route(name="publish_document_uploadDoctype", path="/Documents/Upload/Doctype", methods={"GET","HEAD","POST"})
     *
     * @param Request         $request
     * @param DocumentManager $documentManager
     * @param KernelInterface $kernel
     *
     * @return Response
     */
    public function uploadDoctype(
        Request $request,
        DocumentManager $documentManager,
        KernelInterface $kernel
    ) {
        if (
            !($response = $this->upload(
                $request,
                true,
                [],
                $documentManager,
                $kernel
            ))
        ) {
            // Si tout est ok
            return $this->redirectToRoute('publish_documenttype_index');
        } else {
            return $response;
        }
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     * @Route(name="publish_document_delete", path="/Documents/Supprimer/{id}", methods={"GET","HEAD","POST"})
     *
     * @param Document        $doc
     * @param KernelInterface $kernel
     *
     * @return Response
     */
    public function delete(Document $doc, KernelInterface $kernel)
    {
        $em = $this->getDoctrine()->getManager();
        $doc->setProjectDir($kernel->getProjectDir());

        if ($doc->getRelation()) {
            // Cascade sucks
            $relation = $doc->getRelation()->setDocument();
            $doc->setRelation(null);
            $em->remove($relation);
            $em->flush();
        }
        $this->addFlash('success', 'Document supprimé');
        $em->remove($doc);
        $em->flush();

        return $this->redirectToRoute('publish_documenttype_index');
    }

    private function upload(
        Request $request,
        $deleteIfExist = false,
        $options = [],
        DocumentManager $documentManager,
        KernelInterface $kernel
    ) {
        $document = new Document();
        $document->setProjectDir($kernel->getProjectDir());
        if (count($options)) {
            $relatedDocument = new RelatedDocument();
            $relatedDocument->setDocument($document);
            $document->setRelation($relatedDocument);
            if (array_key_exists('etude', $options)) {
                $relatedDocument->setEtude($options['etude']);
            }
            if (array_key_exists('etudiant', $options)) {
                $relatedDocument->setMembre($options['etudiant']);
            }
        }

        $form = $this->createForm(DocumentType::class, $document, $options);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $documentManager->uploadDocument(
                    $document,
                    null,
                    $deleteIfExist
                );

                return false;
            }
        }

        return $this->render('Publish/Document/upload.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function reupload(
        Request $request,
        $deleteIfExist = true,
        $options = [],
        DocumentManager $documentManager,
        KernelInterface $kernel, 
        Document $document
    ) {
        $document->setProjectDir($kernel->getProjectDir());
        $relatedDocument = $document->getRelation();

        $form = $this->createForm(DocumentType::class, $document, $options);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $documentManager->uploadDocument(
                    $document,
                    $relatedDocument,
                    $deleteIfExist
                );

                return false;
            }
        }

        return $this->render('Publish/Document/upload.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Security("has_role('ROLE_QUALITE') || has_role('ROLE_SUIVEUR')")
     * @Route("/Documents/notify_qualite/{id} ", name="relecture_notify_qualite", methods={"GET", "POST"})
     */
    public function notifyQualite(
        Request $request,
        Document $doc,
        \Swift_Mailer $mailer
    ) {
        $suiveurQualite = $doc
            ->getRelation()
            ->getEtude()
            ->getSuiveurQualite();
        $success_message = "Le mail a été envoyé";
        if ($suiveurQualite) {
            $to = $suiveurQualite->getEmail();
            $message = (new \Swift_Message('Notification Qualite'))
                ->setFrom('robot@junior-aei.com')
                ->setTo($to)
                ->setBody(
                    $this->renderView('Relecture/Mail/mailQualite.html.twig', [
                        'doc' => $doc
                    ]),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', $success_message);
        } else {
            $this->addFlash(
                'warning',
                "Il n'y a pas de suiveur qualite pour cette etude"
            );
        }

        return $this->render('Publish/Document/voir.html.twig', [
            'doc' => $doc
        ]);
    }

    /**
     * @Security("has_role('ROLE_QUALITE') || has_role('ROLE_SUIVEUR')")
     * @Route("/Documents/notify_etude/{id} ", name="relecture_notify_etude", methods={"GET", "POST"})
     */
    public function notifyEtude(
        Request $request,
        Document $doc,
        \Swift_Mailer $mailer
    ) {
        $success_message = "Le mail a été envoyé";
        $suiveur = $doc
            ->getRelation()
            ->getEtude()
            ->getSuiveur();
        if ($suiveur) {
            $to = $suiveur->getEmail();

            $message = (new \Swift_Message('Notification Etude'))
                ->setFrom('robot@junior-aei.com')
                ->setTo($to)
                ->setBody(
                    $this->renderView('Relecture/Mail/mailEtude.html.twig', [
                        'doc' => $doc
                    ]),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', $success_message);
        } else {
            $this->addFlash(
                'warning',
                "Il n'y a pas de suiveur pour cette etude"
            );
        }

        return $this->render('Publish/Document/voir.html.twig', [
            'doc' => $doc
        ]);
    }
}
