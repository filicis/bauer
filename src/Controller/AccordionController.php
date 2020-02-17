<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AccordionController extends AbstractController
{
    /**
     * @Route("/accordion", name="accordion")
     */
    public function index(Request $request)
    {
  		$locale= $request->getLocale();

        return $this->render('accordion/index.html.twig', [
            'controller_name' => 'AccordionController',
            'locale' => $locale,
        ]);
    }
}
