<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Form\SourcesType;
use App\Repository\SourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sources")
 */
class SourcesController extends AbstractController
{
    /**
     * @Route("/", name="sources_index", methods={"GET"})
     */
    public function index(SourcesRepository $sourcesRepository): Response
    {
        return $this->render('sources/index.html.twig', [
            'sources' => $sourcesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sources_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $source = new Sources();
        $form = $this->createForm(SourcesType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($source);
            $entityManager->flush();

            return $this->redirectToRoute('sources_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sources/new.html.twig', [
            'source' => $source,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sources_show", methods={"GET"})
     */
    public function show(Sources $source): Response
    {
        return $this->render('sources/show.html.twig', [
            'source' => $source,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sources_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sources $source): Response
    {
        $form = $this->createForm(SourcesType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sources_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sources/edit.html.twig', [
            'source' => $source,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sources_delete", methods={"POST"})
     */
    public function delete(Request $request, Sources $source): Response
    {
        if ($this->isCsrfTokenValid('delete'.$source->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($source);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sources_index', [], Response::HTTP_SEE_OTHER);
    }
}
