<?php

namespace App\Controller;

use App\Entity\Intern;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InternController extends AbstractController
{
    #[Route('/intern', name: 'app_intern')]
    public function index( ManagerRegistry $doctrine ): Response
    {
        $interns = $doctrine->getRepository( Intern::class)->findBy([], ["name" => "ASC"]);
        
        return $this->render('intern/index.html.twig', [
            'interns' => $interns,
        ]);
    }

    #[Route('/intern/{id}', name: 'show_intern')]
    public function show(Intern $intern): Response 
    {

        return $this->render('intern/show.html.twig', [
            'intern' => $intern
        ]);
    }
}
