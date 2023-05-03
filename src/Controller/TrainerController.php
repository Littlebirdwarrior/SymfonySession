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
        $trainers = $doctrine->getRepository( Trainer::class )->findBy([], ["name" => "ASC"]);
        return $this->render('trainer/index.html.twig', [
            'trainers' => $trainers,
        ]);
    }

    #[Route('/trainer/{id}', name: 'show_trainer')]
    public function show(Trainer $trainer): Response 
    {
        return $this->render('trainer/show.html.twig', [
            'trainer' => $trainer
        ]);
    }
}
