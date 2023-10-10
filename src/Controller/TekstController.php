<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\KalenderTekst;


class TekstController extends AbstractController
{
	/**
	* @ Route("/admin/tekst", name="tekst")
	*/

  #[Route('/admin/tekst', name: 'tekst')]
	public function index(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(KalenderTekst::class);
    $tekster = $repository->findAll();

		return $this->render('tekst/index.html.twig', [
		'controller_name' => 'TekstController',
		'tekster' => $tekster,
		]);
	}
}
