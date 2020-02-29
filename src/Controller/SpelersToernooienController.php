<?php

namespace App\Controller;

use App\Entity\SpelersToernooien;
use App\Form\SpelersToernooienType;
use App\Repository\SpelersToernooienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/spelers_toernooien")
 */
class SpelersToernooienController extends AbstractController
{
    /**
     * @Route("/", name="spelers_toernooien_index", methods={"GET"})
     */
    public function index(SpelersToernooienRepository $spelersToernooienRepository): Response
    {
        return $this->render('spelers_toernooien/index.html.twig', [
            'spelers_toernooiens' => $spelersToernooienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="spelers_toernooien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $spelersToernooien = new SpelersToernooien();
        $form = $this->createForm(SpelersToernooienType::class, $spelersToernooien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($spelersToernooien);
            $entityManager->flush();

            return $this->redirectToRoute('spelers_toernooien_index');
        }

        return $this->render('spelers_toernooien/new.html.twig', [
            'spelers_toernooien' => $spelersToernooien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spelers_toernooien_show", methods={"GET"})
     */
    public function show(SpelersToernooien $spelersToernooien): Response
    {
        return $this->render('spelers_toernooien/show.html.twig', [
            'spelers_toernooien' => $spelersToernooien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="spelers_toernooien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SpelersToernooien $spelersToernooien): Response
    {
        $form = $this->createForm(SpelersToernooienType::class, $spelersToernooien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('spelers_toernooien_index');
        }

        return $this->render('spelers_toernooien/edit.html.twig', [
            'spelers_toernooien' => $spelersToernooien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spelers_toernooien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SpelersToernooien $spelersToernooien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$spelersToernooien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($spelersToernooien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('spelers_toernooien_index');
    }
}
