<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AboutController extends AbstractController
{
  	#[Route('/about', name: 'about')]
	public function index(Request $request) : Response
	{
		$locale= $request->getLocale();

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



		return $this->render('about/index.html.twig', [
		'controller_name' => 'CalendarController',
		'locale' => $locale,
		'our_form' => $form,
		'our_form' => $form->createView(),
		]);



		return $this->render('about/index.html.twig', [
		'controller_name' => 'AboutController',
		'locale' => $locale,
		]);
	}
}
