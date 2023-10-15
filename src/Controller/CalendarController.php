<?php

/**
* This file is part of the Bauer package.
*
* @author Michael Lindhardt Rasmussen <filicis@gmail.com>
* @copyright 2000-2023 Filicis Software
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace App\Controller;

// use App\Form\AarstalType;

use Symfony\Contracts\Translation\TranslatorInterface;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;


use App\Utils\Bauer;
use App\Utils\BauerIT;


  /**
   * CalendarController
   *
   * H?ndterer indtil videre kun Dansk/Norsk Kalender
   *
   **/

class CalendarController extends AbstractController
{
	public $aarstal= 1850;


  	#[Route('/calendar1/{!aarstal}', name: 'calendar1')]
	//  #[Route('/ggg', name: 'app_defaultttt')]
	public function index1(Request $request, $aarstal =1711) : Response
	{
		$session = $request->getSession();
		$session->set('Bauer', $aarstal);
		$locale= $request->getLocale();
		$bauer= new Bauer();
		$bauer->setYear($aarstal);


		return $this->render('calendar/index.html.twig', [
		'controller_name' => 'CalendarController',
		'bauer' => $bauer,
		'locale' => $locale,
		]);
	}


	/**
	* @ Route("/calendar/{aarstal}", name="calendar")
	*/

   #[Route('/calendar/{aarstal<\d+>?1711}', name: 'calendar')]
	public function index(Request $request, $aarstal = 1711)
	{
        $session = $request->getSession();
		$locale= $request->getLocale();
		$bauer= new Bauer();
		$bauer->setYear($aarstal);
		if ($bauer->isValid() == False)
		return $this->redirectToRoute('calendar', ['aarstal' => 1700]);

		$session->set('aarstal', $aarstal);


		$test= ['message' => 'Entest'];
		$form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
		->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => '?rstal', 'aria-label' => 'Search']]*/ )
		->add('send', SubmitType::class , ['attr' => ['label' => 'V?lg']] )
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


	/**
	* Italiensk kalaneder (Den katolske kirke)
	*/

	#[Route('/calendarIT/{aarstal<\d+>?1711}', name: 'calendarIT')]
	public function indexIT(Request $request, $aarstal = 1711)
	{
        $session = $request->getSession();
		$locale= $request->getLocale();
		$bauer= new BauerIT();
		$bauer->setYear($aarstal);
		if ($bauer->isValid() == False)
		return $this->redirectToRoute('calendar', ['aarstal' => 1700]);

		$session->set('aarstal', $aarstal);


		$test= ['message' => 'Entest'];
		$form= $this->createFormBuilder($test /*, ['attr' => ['class' => 'form-inline']] */ )
		->add('year', TextType::class /*, ['attr' => ['class' => 'form-control mr-sm-2', 'type' => 'number', 'placeholder' => '?rstal', 'aria-label' => 'Search']]*/ )
		->add('send', SubmitType::class , ['attr' => ['label' => 'V?lg']] )
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
