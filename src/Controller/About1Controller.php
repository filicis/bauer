<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class About1Controller extends AbstractController
{
    #[Route('/about1', name: 'app_about1')]
    public function index(): Response
    {
        return $this->render('about1/index.html.twig', [
            'controller_name' => 'About1Controller',
        ]);
    }


}

