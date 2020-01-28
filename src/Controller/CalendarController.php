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
use App\Utils\Bauer;

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
    public function index($aarstal)
    {
        $this->session->set('Bauer', $aarstal);
    	$bauer= new Bauer($aarstal, $this->session->get('isLatin', 'False'));



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
    	$bauer= new Bauer($this->session->get('Bauer', 1960), $this->session->get('isLatin', 'False'));
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
