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
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;


use App\Utils\Bauer;


  /**
   * CalendarController
   *
   * Håndterer indtil videre kun Dansk/Norsk Kalender
   *
   **/

class CalendarController extends AbstractController
{
	private $session;
	public $aarstal= 1850;


	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}



	/**
	* function index()
	*
	* @Route("/calendar1/{aarstal}", name="calendar1")
	*/

	public function index1($aarstal, Request $request)
	{
		$this->session->set('Bauer', $aarstal);
		$bauer= new Bauer();
		$bauer->setYear($aarstal);


		return $this->render('calendar/index.html.twig', [
		'controller_name' => 'CalendarController',
		'bauer' => $bauer,
		]);
	}


	/**
	* @Route("/calendar/{aarstal}", name="calendar")
	*/

	public function index($aarstal, Request $request)
	{

		$locale= $request->getLocale();
		$bauer= new Bauer();
		$bauer->setYear($aarstal);
		if ($bauer->isValid() == False)
		return $this->redirectToRoute('calendar', ['aarstal' => 1700]);

		$this->session->set('aarstal', $aarstal);


		$test= ['message' => 'Entest'];
		$form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
		->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => 'Årstal', 'aria-label' => 'Search']]*/ )
		->add('send', SubmitType::class , ['attr' => ['label' => 'Vælg']] )
		->getForm();

		// $form->get('year')->setData($this->session->get('Bauer', 1960));


		$form->handleRequest($request);



		if($form->isSubmitted() /* && $form->isValid() */ )
		{
			$data= $form->getData();
			$aarstal= $data['year'];

			return $this->redirectToRoute('calendar', ['aarstal' => $aarstal, ]);
		  return $this->redirectToRoute('calendar1', ['_locale' => $_locale, 'aarstal' => $aarstal]);

		}



		return $this->render('calendar/index.html.twig', [
		'controller_name' => 'CalendarController',
		'locale' => $locale,
		'bauer' => $bauer,
		'our_form' => $form,
		'our_form' => $form->createView(),
		]);
	}






}
