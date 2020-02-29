<?php
namespace App\Controller;

use App\Entity\SpelersToernooien;
use App\Entity\Spelers;
use App\Entity\Scholen;
use App\Form\XMLImportType;
use App\Repository\ScholenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/toernooi={id}/spelers-toevoegen", name="file_spelers_toevoegen",  methods={"GET","POST"})
     */
    public function ImportXmlFile(Request $request, $id): Response
    {
        $form = $this->createForm(XMLImportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $xmlelementen = simplexml_load_file($form['my_file']->getData());

            return $this->render('default/see_xml_file.html.twig',[
                'id' => $id,
                'xml' => $xmlelementen,
            ]);
        }

        return $this->render('default/import_xml_file.html.twig',[
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/toernooi/spelers-toevoegen=db", name="file_spelers_toevoegen_db",  methods={"GET","POST"})
     */
    public function SlaXmlDataOp(Request $request, scholenRepository $scholenRepository): Response
    {
        $id=3;
        $em = $this->getDoctrine()->getManager();
        $toernooi = $em->getRepository('App:Toernooien')->findOneBy(array('id' => $id));

        $form = $this->createForm(XMLImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $xml = simplexml_load_file($form['my_file']->getData());

            foreach ($xml as $datarow)
            {
                $speler = new Spelers();
                $tussentabel = new SpelersToernooien();

                $speler->setVoornaam($datarow->voornaam);
                $speler->setTussenvoegsel($datarow->tussenvoegsel);
                $speler->setAchternaam($datarow->achternaam);
                $schoolspeler = $em->getRepository('App:Scholen')->findOneBy(array('naam' => $datarow->school));
                // als school nog niet bestaat dan een nieuwe school maken
                if ($schoolspeler == null) {
                    $school = new Scholen();
                    $school->setNaam($datarow->school);
                    $em->persist($school);
                    $em->flush();
                    $schoolspeler = $em->getRepository('App:Scholen')->findOneBy(array('naam' => $datarow->school));
                }
                $speler->setSchoolId($schoolspeler);
                // eerst speler opslaan anders heb je geen id.
                $em->persist($speler);
                $em->flush();
                $tussentabel->setSpelerId($speler);
                $tussentabel->setToernooiId($toernooi);

                $em->persist($tussentabel);
                $em->flush();

            }
            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/push_xml_file.html.twig',[
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }

}

?>