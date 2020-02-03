<?php

namespace App\Controller;

// use App\Form\AarstalType;

use Symfony\Contracts\Translation\TranslatorInterface;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


use App\Utils\Bauer;
use App\Entity\Bid;
use App\Entity\Kalender;
use App\Entity\KalenderTekst;
use App\Entity\Locale;

use App\Repository\KalenderTekstRepository;

class CalendarController extends AbstractController
{
    private $session;
	  public $aarstal= 1850;


	public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }



    /**
     * @Route("/calendar/{aarstal}", name="calendar1")
     */

    public function index($aarstal, Request $request)
    {
/**
     $entityManager = $this->getDoctrine()->getManager();

     $bid= $this->getDoctrine()->getRepository(Bid::class)->find(2);
     $kalender= $this->getDoctrine()->getRepository(Kalender::class)->find(1);
     $locale= $this->getDoctrine()->getRepository(Locale::class)->find(1);


     $product = new KalenderTekst;
     $product->setLocale($locale);
     $product->setKalender($kalender);
     $product->setBid($bid);

     $product->setTekst(["Ma", "Ti", "On", "To", "Fr", "Lo", "So", ]);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
     $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
     $entityManager->flush();
**/



        $this->session->set('Bauer', $aarstal);
    	$bauer= new Bauer($this->getDoctrine()->getRepository(KalenderTekst::class));
      $bauer->setAar($aarstal);
      $bauer->setLocale($request->attributes->get('_locale'));


        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'bauer' => $bauer,
        ]);
    }


    /**
     * @Route("/", name="calendar")
     */

    public function index1(TranslatorInterface $translator, Request $request)
    {
        $translator->setLocale('la');
    	$bauer= new Bauer($this->getDoctrine()->getRepository(KalenderTekst::class));
    	$bauer->setAar($this->session->get('Bauer', 1960));
    	$bauer->setLocale($request->attributes->get('_locale') );
    	if ($bauer->isValid() == False)
        return $this->redirectToRoute('about');


        $test= ['message' => 'Entest'];
        $form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
          ->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => 'Årstal', 'aria-label' => 'Search']]*/ )
          ->add('send', SubmitType::class , ['attr' => ['label' => 'Vælg']] )
          ->getForm();

        $form->get('year')->setData($this->session->get('Bauer', 1960));


    	$form->handleRequest($request);



    	if($form->isSubmitted() /* && $form->isValid() */ )
    	{
            $data= $form->getData();
            $this->session->set('Bauer', $data['year']);

             return $this->redirectToRoute('calendar');

    	}



        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'bauer' => $bauer,
            'our_form' => $form,
            'our_form' => $form->createView(),
        ]);
    }






}
