<?php

namespace App\Controller;

use App\Entity\Scholen;
use App\Form\ScholenType;
use App\Repository\ScholenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/scholen")
 */
class ScholenController extends AbstractController
{
    /**
     * @Route("/", name="scholen_index", methods={"GET"})
     */
    public function index(ScholenRepository $scholenRepository): Response
    {
        return $this->render('scholen/index.html.twig', [
            'scholens' => $scholenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="scholen_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $scholen = new Scholen();
        $form = $this->createForm(ScholenType::class, $scholen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($scholen);
            $entityManager->flush();

            return $this->redirectToRoute('scholen_index');
        }

        return $this->render('scholen/new.html.twig', [
            'scholen' => $scholen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="scholen_show", methods={"GET"})
     */
    public function show(Scholen $scholen): Response
    {
        return $this->render('scholen/show.html.twig', [
            'scholen' => $scholen,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="scholen_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Scholen $scholen): Response
    {
        $form = $this->createForm(ScholenType::class, $scholen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('scholen_index');
        }

        return $this->render('scholen/edit.html.twig', [
            'scholen' => $scholen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="scholen_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Scholen $scholen): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scholen->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($scholen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('scholen_index');
    }
}
