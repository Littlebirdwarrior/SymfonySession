<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Entity\Session;
use App\Repository\InternRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class InternController extends AbstractController
{
    //*listes
    #[Route('/intern', name: 'app_intern')]
    public function index( ManagerRegistry $doctrine ): Response
    {
        $interns = $doctrine->getRepository( Intern::class)->findBy([], ["name" => "ASC"]);
        
        return $this->render('intern/index.html.twig', [
            'interns' => $interns,
        ]);
    }

    //*quitter la session pour un stagiaire
    #[Route("/session/removeSession/{idS}/{idI}", name: 'removeSession')]
    // ParamConverter permet de convertir les parametres en instances de Session et de Stagiaire en utilisant l'injection de
    // dependance de Doctrine pour recuper les entités correspondant à la base de donnée
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("intern", options:["mapping"=>["idI"=>"id"]])]
    
    public function removeStagiaire(ManagerRegistry $doctrine, Session $session, Intern $intern)
    {
        $em = $doctrine->getManager();
        $intern->removeSession($session);
        $em->persist($intern);
        $em->flush();

        return $this->redirectToRoute('show_intern', ['id' => $intern->getId()]);
    }  

    //*s'inscire à une session
    #[Route("/session/addSession/{idS}/{idI}", name: 'addSession')]
    
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("intern", options:["mapping"=>["idI"=>"id"]])]
    
    public function addSession(ManagerRegistry $doctrine, Session $session, Intern $intern)
    {
        $em = $doctrine->getManager();
        $intern->addSession($session);
        $em->persist($intern);
        $em->flush();

        return $this->redirectToRoute('show_intern', ['id' => $intern->getId()]);
    } 
    
    //*details
    #[Route('/intern/{id}', name: 'show_intern')]
    public function show( InternRepository $internRepository, Intern $intern): Response 
    {
        $intern_id = $intern->getId();

        $sessionsLeft = $internRepository->getSessionLeft($intern_id);

        return $this->render('intern/show.html.twig', [
            'intern' => $intern,
            'sessionsLeft' => $sessionsLeft
        ]);
    }
}
