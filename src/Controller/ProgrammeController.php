<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $programmes = $doctrine->getRepository( Programme::class)->findBy([], ["moduleDuration" => "ASC"]);
        
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }
}
