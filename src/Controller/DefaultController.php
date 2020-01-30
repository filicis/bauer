<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

  /**
   *	DefaultController
   *
   *
   **/

class DefaultController extends AbstractController
{

	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	/**
	 * @Route("/default", name="default")
	 *
	 **/

	public function index(Request $request)
	{
		$request->getSession()->set('_locale', 'da');

	  $url = $this->generateUrl('calendar1', ['aarstal' => 1735]);

    return $this->redirectToRoute('calendar1', ['aarstal' => 1736]);

    return $this->redirectToRoute('calendar1');

		$response = new RedirectResponse('calendar');
		$response->send();


		return $this->render('default/index.html.twig', [
		'controller_name' => 'DefaultController',
		]);
	}
}
