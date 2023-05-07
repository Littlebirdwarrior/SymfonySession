<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Intern;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
