<?php

namespace App\Controller;

use App\Entity\Sensors;
use App\Form\SensorsType;
use App\Repository\SensorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sensors")
 */
class SensorsController extends AbstractController
{
    /**
     * @Route("/", name="sensors_index", methods={"GET"})
     */
    public function index(SensorsRepository $sensorsRepository): Response
    {
        return $this->render('sensors/index.html.twig', [
            'sensors' => $sensorsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sensors_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sensor = new Sensors();
        $form = $this->createForm(SensorsType::class, $sensor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sensor);
            $entityManager->flush();

            return $this->redirectToRoute('sensors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sensors/new.html.twig', [
            'sensor' => $sensor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sensors_show", methods={"GET"})
     */
    public function show(Sensors $sensor): Response
    {
        return $this->render('sensors/show.html.twig', [
            'sensor' => $sensor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sensors_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sensors $sensor): Response
    {
        $form = $this->createForm(SensorsType::class, $sensor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sensors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sensors/edit.html.twig', [
            'sensor' => $sensor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sensors_delete", methods={"POST"})
     */
    public function delete(Request $request, Sensors $sensor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sensor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sensor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sensors_index', [], Response::HTTP_SEE_OTHER);
    }
}
