<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

	public function index(SessionInterface $session)
	{
		return $this->render('default/index.html.twig', [
		'controller_name' => 'DefaultController',
		]);
	}
}
