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
	private $session;

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
		$aarstal= $this->session->get('aarstal', 1737);
		$_locale= $this->session->get('_locale', 'da');


		$request->getSession()->set('_locale', 'en');


		return $this->redirectToRoute('calendar', ['_locale' => $_locale, 'aarstal' => $aarstal]);

	}


	/**
	* @Route("/setLocale", name="setLocale")
	*/


	public function setLocale(Request $request)
	{
		$locale = $request->attributes->get('_locale');
		$this->session->set('_locale', $locale);
		$aarstal= $this->session->get('aarstal', 1700);

		return $this->redirectToRoute('calendar', ['aarstal' => $aarstal, ]);

		return $this->redirectToRoute('default');
	}

}
