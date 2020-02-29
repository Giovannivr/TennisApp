<?php

namespace App\Controller;

use App\Entity\Spelers;
use App\Entity\SpelersToernooien;
use App\Entity\Toernooien;
use App\Form\SpelersType;
use App\Form\Spelers2Type;
use App\Repository\SpelersRepository;
use App\Repository\SpelersToernooienRepository;
use App\Repository\ToernooienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/spelers")
 */
class SpelersController extends AbstractController
{
    /**
     * @Route("/", name="spelers_index", methods={"GET"})
     */
    public function index(SpelersRepository $spelersRepository): Response
    {
        return $this->render('spelers/index.html.twig', [
            'spelers' => $spelersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="spelers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $speler = new Spelers();
        $form = $this->createForm(SpelersType::class, $speler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($speler);
            $entityManager->flush();

            return $this->redirectToRoute('spelers_index');
        }

        return $this->render('spelers/new.html.twig', [
            'speler' => $speler,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new2/{id}", name="spelers_new2", methods={"GET","POST"})
     */
    public function new2(Request $request, $id): Response
    {
        $speler = new Spelers();
        $form = $this->createForm(SpelersType::class, $speler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($speler);
            $entityManager->flush();

            $toernooi = $entityManager->getRepository('App:Toernooien')->findOneBy(array('id' => $id));

            $spelertoernooi = new SpelersToernooien();
            $spelertoernooi->setSpelerId($speler);
            $spelertoernooi->setToernooiId($toernooi);
            $entityManager->persist($spelertoernooi);
            $entityManager->flush();


            return $this->redirectToRoute('homepage');
        }

        return $this->render('spelers/new2.html.twig', [
            'speler' => $speler,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/lijst/{id}", name="spelers_lijst", methods={"GET","POST"})
     */
    public function lijst(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $spelerstus = $entityManager->getRepository('App:SpelersToernooien')->findBy(array('toernooiId' => $id));

        $spelersids = [];
        // bepaal voor iedere tussentabel het bijbehorende ID van de speler(s)
        foreach ($spelerstus as $speler) {
            $ids = $speler->getSpelerId()->getId();
                // voeg het gevonden speler id toe aan het array dat de aangemelde spelers heeft
            array_push($spelersids, $ids);
        }


        $spelers = $entityManager->getRepository('App:Spelers')->findBy(array('id' => $spelersids));
        $toernooi = $entityManager->getRepository('App:Toernooien')->findBy(array('id' => $id));


        return $this->render('spelers/lijst.html.twig', [
            'spelers' => $spelers,
            'toernooi' => $toernooi,
        ]);

    }

    /**
     * @Route("/{id}", name="spelers_show", methods={"GET"})
     */
    public function show(Spelers $speler): Response
    {
        return $this->render('spelers/show.html.twig', [
            'speler' => $speler,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="spelers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Spelers $speler): Response
    {
        $form = $this->createForm(SpelersType::class, $speler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('spelers_index');
        }

        return $this->render('spelers/edit.html.twig', [
            'speler' => $speler,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spelers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Spelers $speler): Response
    {
        if ($this->isCsrfTokenValid('delete'.$speler->getId(), $request->request->get('_token'))) {
            $spelersid = $speler->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($speler);


            $spelertoernooi = $entityManager->getRepository('App:SpelersToernooien')->findOneBy(array('spelerId' => $spelersid));
            $entityManager->remove($spelertoernooi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('spelers_index');
    }
}
