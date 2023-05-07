<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Entity\Session;
use Doctrine\ORM\Query\Parameter;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository( Session::class)->findBy([], ["startDate" => "DESC"]);


        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }

    #[Route("/session/removeIntern/{idS}/{idI}", name: 'removeIntern')]
    // ParamConverter permet de convertir les parametres en instances de Session et de Stagiaire en utilisant l'injection de
    // dependance de Doctrine pour recuper les entités correspondant à la base de donnée
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("intern", options:["mapping"=>["idI"=>"id"]])]
    
    public function removeStagiaire(ManagerRegistry $doctrine, Session $session, Intern $intern){
        $em = $doctrine->getManager();
        $session->removeIntern($intern);
        $em->persist($session);
        $em->flush();

    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
}   

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response 
    {
        $session_id = $session->getId();


        // Récupérez les stagiaire non insccrit
        $nonSubscribers = $sessionRepository->getNonSubscriber($session_id);

        if (!empty($nonSubscribers) && $nonSubscribers) 
        {
            $countNS = count($nonSubscribers);
        } else {
            $countNS = 0;
        }

        //Recuperer les programmes non programmer
        $nonProgrammed = $sessionRepository->getNonProgrammed($session_id);

        if (!empty($nonProgrammed) && $nonProgrammed) 
        {
            $countNP = count($nonProgrammed);
        } else {
            $countNP = 0;
        }

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonSubscribers' => $nonSubscribers,
            'countNS' => $countNS,
            'nonProgrammed' => $nonProgrammed,
            'countNP' => $countNP,
        ]);
    }

}
