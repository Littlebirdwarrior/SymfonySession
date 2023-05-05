<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Intern;
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
    public function show(ManagerRegistry $doctrine, Session $session, Intern $intern): Response 
    {
        $session_id = $session->getId();

        // Créez une instance de QueryBuilder pour construire la requête
        $queryBuilder = $doctrine->getRepository( Intern::class)->createQueryBuilder();

        // Construisez la requête
        $queryBuilder->select('i')
            ->from(Intern::class, 'i')
            ->join('i.sessions', 's')
            ->where('s.id <> :sessionId')
            ->setParameter('sessionId', 1);

        // Récupérez les résultats de la requête
        $interns = $queryBuilder->getQuery()->getResult();


        return $this->render('session/show.html.twig', [
            'session' => $session,
            'interns' => $interns
        ]);
    }

}
