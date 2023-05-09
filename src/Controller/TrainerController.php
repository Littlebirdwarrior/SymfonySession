<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Trainer;
use App\Repository\TrainerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    //*quitter la session pour un enseignant
    #[Route("/session/removeSession/{idS}/{idI}", name: 'removeSession')]
    // ParamConverter permet de convertir les parametres en instances de Session et de Stagiaire en utilisant l'injection de
    // dependance de Doctrine pour recuper les entités correspondant à la base de donnée
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("trainer", options:["mapping"=>["idI"=>"id"]])]
    
    public function removeSession(ManagerRegistry $doctrine, Session $session, trainer $trainer)
    {
        $em = $doctrine->getManager();
        $trainer->removeSession($session);
        $em->persist($trainer);
        $em->flush();

        return $this->redirectToRoute('show_trainer', ['id' => $trainer->getId()]);
    }  

    //*s'inscire à une session pour un enseignant
    #[Route("/session/addSession/{idS}/{idI}", name: 'addSession')]
    
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("trainer", options:["mapping"=>["idI"=>"id"]])]
    
    public function addSession(ManagerRegistry $doctrine, Session $session, Trainer $trainer)
    {
        $em = $doctrine->getManager();
        $trainer->addSession($session);
        $em->persist($trainer);
        $em->flush();

        return $this->redirectToRoute('show_trainer', ['id' => $trainer->getId()]);
    } 

    #[Route('/trainer/{id}', name: 'show_trainer')]
    public function show(TrainerRepository $trainerRepository, Trainer $trainer): Response 
    {
        $trainer_id = $trainer->getId();

        $sessionsLeft = $trainerRepository->getSessionLeft($trainer_id);

        return $this->render('trainer/show.html.twig', [
            'trainer' => $trainer,
            'sessionsLeft' => $sessionsLeft
        ]);
    }
}
