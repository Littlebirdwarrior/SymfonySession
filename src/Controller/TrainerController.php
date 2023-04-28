<?php

namespace App\Controller;

use App\Entity\Trainer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainerController extends AbstractController
{
    #[Route('/trainer', name: 'app_trainer')]
    public function index( ManagerRegistry $doctrine): Response
    {
        $trainers = $doctrine->getRepository( Trainer::class )->findAll();
        return $this->render('trainer/index.html.twig', [
            'trainers' => $trainers,
        ]);
    }
}
