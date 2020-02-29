<?php

namespace App\Controller;

use App\Entity\Toernooien;
use App\Form\ToernooienType;
use App\Repository\ToernooienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/toernooien")
 */
class ToernooienController extends AbstractController
{
    /**
     * @Route("/", name="toernooien_index", methods={"GET"})
     */
    public function index(ToernooienRepository $toernooienRepository): Response
    {
        return $this->render('toernooien/index.html.twig', [
            'toernooiens' => $toernooienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index2", name="toernooien_index2", methods={"GET"})
     */
    public function index2(ToernooienRepository $toernooienRepository): Response
    {
        return $this->render('toernooien/index2.html.twig', [
            'toernooiens' => $toernooienRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="toernooien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $toernooien = new Toernooien();
        $form = $this->createForm(ToernooienType::class, $toernooien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($toernooien);
            $entityManager->flush();

            return $this->redirectToRoute('toernooien_index');
        }

        return $this->render('toernooien/new.html.twig', [
            'toernooien' => $toernooien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="toernooien_show", methods={"GET"})
     */
    public function show(Toernooien $toernooien): Response
    {
        return $this->render('toernooien/show.html.twig', [
            'toernooien' => $toernooien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="toernooien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Toernooien $toernooien): Response
    {
        $form = $this->createForm(ToernooienType::class, $toernooien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('toernooien_index');
        }

        return $this->render('toernooien/edit.html.twig', [
            'toernooien' => $toernooien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="toernooien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Toernooien $toernooien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toernooien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($toernooien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('toernooien_index');
    }
}
