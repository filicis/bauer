<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class SprogController extends AbstractController
{

		public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }



    /**
     * @Route("/sprog", name="sprog")
     */
    public function index()
    {
        return $this->render('sprog/index.html.twig', [
            'controller_name' => 'SprogController',
        ]);
    }


    /**
     * @Route("/sprog/dansk", name="tilDansk")
     */
    public function dansk(Request $request)
    {
    	 $this->session->set('isLatin', false);

        return $this->redirectToRoute('calendar');
        return $this->render('sprog/index.html.twig', [
            'controller_name' => 'SprogController',
        ]);
    }


    /**
     * @Route("/sprog/latin", name="tilLatin")
     */

    public function latin(Request $request)
    {
    	 $this->session->set('isLatin', true);

       return $this->redirectToRoute('calendar');
        return $this->render('sprog/index.html.twig', [
            'controller_name' => 'SprogController',
        ]);
    }





}
