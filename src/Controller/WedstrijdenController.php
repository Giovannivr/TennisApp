<?php

namespace App\Controller;

use App\Entity\Wedstrijden;
use App\Form\WedstrijdenType;
use App\Repository\WedstrijdenRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wedstrijden")
 */
class WedstrijdenController extends AbstractController
{
    /**
     * @Route("/", name="wedstrijden_index", methods={"GET"})
     */
    public function index(WedstrijdenRepository $wedstrijdenRepository): Response
    {
        return $this->render('wedstrijden/index.html.twig', [
            'wedstrijdens' => $wedstrijdenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/uitslagen/{id}", name="uitslagen_toernooi", methods={"GET"})
     */
    public function uitslagen(WedstrijdenRepository $wedstrijdenRepository, $id): Response
    {
        return $this->render('wedstrijden/index2.html.twig', [
            'wedstrijdens' => $wedstrijdenRepository->findBy(array('toernooiId' => $id)),
        ]);
    }


    /**
     * @Route("/maak_wedstrijd/{id}", name="maak_wedstrijd")
     */
    public function maakwedstrijd(WedstrijdenRepository $wedstrijdenRepository, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();

        if ( ($em->getRepository('App:Wedstrijden')->findBy(array('toernooiId' => $id)) == null) ) {


            $allespelerstussentabel = $em->getRepository('App:SpelersToernooien')->findBy(array('toernooiId' => $id));

            $spelerids = [];
            foreach ($allespelerstussentabel as $speler) {
                // als speler ingeschreven is.
                array_push($spelerids, $speler->getSpelerId());
            }

            $toernooiid = $id;
            $aantalspelers = count($spelerids);
            $i = 1;
            $aantalronden = 0;
            // bereken aantal ronden
            while ($aantalspelers > $i) {
                $i = $i * 2;
                $aantalronden++;
            }

            // formule voor berekenen aantal vrije spelers 1ste ronde
            $aantalvrijespelersronde1 = pow(2, $aantalronden) - $aantalspelers;

            $aantaldeelnemersronde1 = $aantalspelers - $aantalvrijespelersronde1;

            shuffle($spelerids);
            $ronde = 1;
            for ($i = 1; $i < $aantaldeelnemersronde1; $i++) {
                $wedstrijd = new Wedstrijden();
                $wedstrijd->setRonde($ronde);
                $wedstrijd->setSpeler1Id($em->getRepository('App:Spelers')->findOneBy(['id' => $spelerids[$i - 1]]));
                $wedstrijd->setSpeler2Id($em->getRepository('App:Spelers')->findOneBy(['id' => $spelerids[++$i - 1]]));
                $wedstrijd->setToernooiId($em->getRepository('App:Toernooien')->findOneBy(['id' => $toernooiid]));
                $em->persist($wedstrijd);
                $em->flush();
                $wijzerarrayspelers = $i;
            }
            $ronde++;

            $aantalronden = $aantalronden - 2;

            while ($aantalronden >= 0) {
                // bereken het aantal wedstrijden voor de volgende ronde
                $aantalwedstrijden = pow(2, $aantalronden);
                for ($i = 1; $i <= $aantalwedstrijden; $i++) {
                    $wedstrijd = new Wedstrijden();
                    $wedstrijd->setRonde($ronde);
                    // vul overgebleven spelers die vrij zijn eerste ronde in bij 2e ronde
                    if ($ronde == 2 && $aantalvrijespelersronde1 > 0) {
                        // als nog 2 of meer spelers dan allebei de spelers invullen
                        if ($aantalvrijespelersronde1 >= 2) {

                            $wedstrijd->setSpeler1Id($em->getRepository('App:Spelers')->findOneBy(['id' => $spelerids[$wijzerarrayspelers]]));
                            $wedstrijd->setSpeler2Id($em->getRepository('App:Spelers')->findOneBy(['id' => $spelerids[++$wijzerarrayspelers]]));
                            $wijzerarrayspelers++;
                            $aantalvrijespelersronde1 = $aantalvrijespelersronde1 - 2;

                            //als nog maar 1 speler over is dan maar 1 speler invullen
                        } elseif ($aantalvrijespelersronde1 == 1) {
                            $wedstrijd->setSpeler1Id($em->getRepository('App:Spelers')->findOneBy(['id' => $spelerids[$wijzerarrayspelers]]));
                            $aantalvrijespelersronde1 = $aantalvrijespelersronde1 - 1;
                            $wijzerarrayspelers++;
                        }
                    }

                    $wedstrijd->setToernooiId($em->getRepository('App:Toernooien')->findOneBy(['id' => $toernooiid]));
                    $em->persist($wedstrijd);
                    $em->flush();
                }
                $aantalronden--;
                $ronde++;
            }


            return $this->render('wedstrijden/melding.html.twig', [
                'melding' => 2,
            ]);



        } else {

            return $this->render('wedstrijden/melding.html.twig', [
                'melding' => 1,
            ]);

        }
    }

    /**
     * @Route("/verwerkgegevens/{id}", name="verwerk_gegevens")
     */
    public function verwerkgegevens(WedstrijdenRepository $wedstrijdenRepository, $id) : Response
    {
        // deze functie zorgt er voor dat er een overzicht per ronde komt waar je de uitslagen
        // per ronde kan invoeren en ook de ronde kan checken zo dat alle uitslagen en winnaars
        // bekend zijn en je kunt dan ook de ronde afsluiten. Die knoppen zitten allemaal in
        // verwerk.html.twig. deze  functie zelft rekent alleen het aantal ronden van het toernooi uit

        $em = $this->getDoctrine()->getManager();
        // zoek alle spelers in de tussentabel die meedoen aan het toernooi en bereken het aantal met count.
        $allespelerstussentabel = $em->getRepository('App:SpelersToernooien')->findBy(array('toernooiId' => $id));
        $aantalspelers = count($allespelerstussentabel);


        // maak een leeg array aan waar je de ronden allemaal apart in gaat zetten
        $aantalronden = [];
        $i=1;
        $j=1;
        // zolang het aantalspelers groter is dan steeds ix2 weet je dat er nog een ronde komt
        // zet dan in het array steeds de ronde aantalronden[0] wordt dan 1 aantalronden [1] wordt 2 enz totdat de while lus stopt
        while ($aantalspelers > $i) {
            $i = $i*2;
            $aantalronden[$j-1]= $j++;
        }

        return $this->render('wedstrijden/verwerk.html.twig', [
            'aantalronden' => $aantalronden,
        ]);
    }


    /**
     * @Route("/checkronde/{id}", name="check_ronde")
     */
    public function checkronde(WedstrijdenRepository $wedstrijdenRepository, $id) : Response
    {
        // voer automatisch voor alle wedstrijden van de ronde de winnaar in
        // check ook nog een keer of er nog ergens geen winnaar is dan de wedstrijden zonder winnaaar teruggeven
        // aan de index.html.twig anders melding OK (omdat index gebruikt wordt "no records found' is dus OK)
        $em = $this->getDoctrine()->getManager();
        $wedstrijden = $wedstrijdenRepository->findBy(['ronde' => $id]);
        foreach ($wedstrijden as $wedstrijd) {
            if ($wedstrijd->getScore1() > $wedstrijd->getScore2()) {
                $wedstrijd->setWinnaarId($wedstrijd->getSpeler1Id());
            } elseif ($wedstrijd->getScore1() < $wedstrijd->getScore2()) {
                $wedstrijd->setWinnaarId($wedstrijd->getSpeler2Id());
            }
            $em->persist($wedstrijd);
            $em->flush();
        }
        return $this->render('wedstrijden/index2.html.twig', [
            // als er geen winnaar bekend is dan laten we die op het scherm zien
            'wedstrijdens' => $wedstrijdenRepository->findBy(['ronde' => $id, 'winnaarId' => null]),
        ]);


    }

    /**
     * @Route("/sluitronde/{id}", name="sluit_ronde")
     */
    public function sluitronde(WedstrijdenRepository $wedstrijdenRepository, $id) : Response
    {
        $em = $this->getDoctrine()->getManager();

        // check of alle wedstrijden wel een winnaar hebben anders mag je niet sluiten
        if ($wedstrijdenRepository->findBy(['ronde' => $id, 'winnaarId' => null])) {

            return $this->render('wedstrijden/melding.html.twig', [
                'melding' => 3,
            ]);

        } else {
            // sluit ronde af en vul spelers in volgende ronde
            $wedstrijden = $wedstrijdenRepository->findBy(['ronde' => $id]);
            $aantalwedstrijden = count($wedstrijden);
            for ($i = 1; $i <= $aantalwedstrijden; $i++) {
                // ga alle wedstrijden van de volgende ronde af of er nog een spelerId op NULL
                // als dat zo is vul dan het winnaarId in van dde wedstrijd van de huidge ronde
                if ($wedstrijdennextround = $wedstrijdenRepository->findBy(['ronde' => $id + 1, 'speler1Id' => null])) {
                    $wedstrijdennextround[0]->setSpeler1Id($wedstrijden[$i - 1]->getWinnaarId());
                    $em->persist($wedstrijdennextround[0]);
                    $em->flush();
                } elseif ($wedstrijdennextround = $wedstrijdenRepository->findBy(['ronde' => $id + 1, 'speler2Id' => null])) {
                    $wedstrijdennextround[0]->setSpeler2Id($wedstrijden[$i - 1]->getWinnaarId());
                    $em->persist($wedstrijdennextround[0]);
                    $em->flush();
                }

            }

            // om direct terug te gaan naar het menu van check ronde en sluit ronde moeten we naar de functie
            // verwerk_gegevens toe met het toernooiID. om het toernooiId te krijgen kunnen we dit uit
            // een wedstrijd halen want daar staat het toernooiid in. We gebruiken de eerste wedstrijd is [0]

            $toernooiid = $wedstrijden[0]->getToernooiId()->getId();

            return $this->redirectToRoute('verwerk_gegevens', ['id' => $toernooiid]);

        }
    }



    /**
     * @Route("/new", name="wedstrijden_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $wedstrijden = new Wedstrijden();
        $form = $this->createForm(WedstrijdenType::class, $wedstrijden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wedstrijden);
            $entityManager->flush();

            return $this->redirectToRoute('wedstrijden_index');
        }

        return $this->render('wedstrijden/new.html.twig', [
            'wedstrijden' => $wedstrijden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wedstrijden_show", methods={"GET"})
     */
    public function show(Wedstrijden $wedstrijden): Response
    {
        return $this->render('wedstrijden/show.html.twig', [
            'wedstrijden' => $wedstrijden,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="wedstrijden_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Wedstrijden $wedstrijden): Response
    {
        $form = $this->createForm(WedstrijdenType::class, $wedstrijden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('wedstrijden_index');
        }

        return $this->render('wedstrijden/edit.html.twig', [
            'wedstrijden' => $wedstrijden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wedstrijden_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Wedstrijden $wedstrijden): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wedstrijden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wedstrijden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wedstrijden_index');
    }
}
