<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainerController extends AbstractController
{
    #[Route('/trainer', name: 'app_trainer')]
    public function index(): Response
    {
        return $this->render('trainer/index.html.twig', [
            'controller_name' => 'TrainerController',
        ]);
    }
}
