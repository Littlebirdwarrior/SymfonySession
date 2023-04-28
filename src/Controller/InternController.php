<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternController extends AbstractController
{
    #[Route('/intern', name: 'app_intern')]
    public function index(): Response
    {
        return $this->render('intern/index.html.twig', [
            'controller_name' => 'InternController',
        ]);
    }
}
