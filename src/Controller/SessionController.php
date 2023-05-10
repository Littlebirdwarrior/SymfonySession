<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use App\Form\SessionType;
use Doctrine\ORM\Query\Parameter;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SessionController extends AbstractController
{
    //*listes
    #[Route('/session', name: 'app_session')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository( Session::class)->findBy([], ["startDate" => "DESC"]);


        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }

    //*add session
    #[Route('/session/{idS}/add', name:'add_session')]
    public function add(EntityManagerInterface $entityManager, Session $session=null, Request $request):Response
    {
        //Creation du formulaire

        $form= $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $session= $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('show_session',array('id' => $session->getFormation()->getId()));
        }

        return $this->render('session/add.html.twig', [
           'formAddSession' => $form->createView(),
        ]);

    }

    //*ajouter ou supprimer un stagiaire de la session

    #[Route("/session/removeIntern/{idS}/{idI}", name: 'removeIntern')]
    // ParamConverter permet de convertir les parametres en instances de Session et de Stagiaire en utilisant l'injection de
    // dependance de Doctrine pour recuper les entités correspondant à la base de donnée
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("intern", options:["mapping"=>["idI"=>"id"]])]
    
    public function removeStagiaire(ManagerRegistry $doctrine, Session $session, Intern $intern)
    {
        $em = $doctrine->getManager();
        $session->removeIntern($intern);
        $em->persist($session);
        $em->flush();

    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }  
    
    #[Route("/session/addIntern/{idS}/{idI}", name: 'addIntern')]
    
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("intern", options:["mapping"=>["idI"=>"id"]])]
    
    public function addStagiaire(ManagerRegistry $doctrine, Session $session, Intern $intern)
    {
        $em = $doctrine->getManager();
        $session->addIntern($intern);
        $em->persist($session);
        $em->flush();

    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    } 

    //**Ajouter ou supprimer un module de la programmation */
    //* voir Session Entity : methode removeProgramme
    #[Route("/session/removeProgramme/{idS}/{idP}", name: 'removeProgramme')]
    
    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("programme", options:["mapping"=>["idP"=>"id"]])]
        
    public function removeProgramme(ManagerRegistry $doctrine, Session $session, Programme $programme)
    {
        $em = $doctrine->getManager();
        $session->removeProgramme($programme);
        // persist(entity) : dit à Doctrine de « persister » l'entité. Cela veut dire qu'à partir de maintenant cette entité (qui n'est qu'un simple objet !) est gérée par Doctrine. Cela n'exécute pas encore de requête SQL, ni rien d'autre.
        $em->persist($session);
        //exécuter effectivement les requêtes nécessaires pour sauvegarder les entités qu'on lui a dit de persister
        $em->flush();
    
    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // //* voir Session Entity : methode addProgramme
    #[Route("/session/addProgramme/{idS}/{idP}", name: 'addProgramme')]

    #[ParamConverter("session", options:["mapping"=>["idS"=>"id"]])]
    #[ParamConverter("programme", options:["mapping"=>["idP"=>"id"]])]
    
    public function addProgramme(ManagerRegistry $doctrine, Session $session, Programme $programme)
    {
        $em = $doctrine->getManager();
        $session->addProgramme($programme);
        $em->persist($session);
        $em->flush();
    return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /** Persist() et flush()
     * persist et flush sont des méthodes importantes de l'EntityManager de Doctrine qui permettent de gérer les entités dans la base de données.
     * 
     * La méthode persist est utilisée pour signaler à Doctrine que l'objet doit être persisté dans la base de données. Elle prend en argument l'objet à persister. Cela signifie que Doctrine va créer une requête SQL pour ajouter l'objet à la base de données lors de la prochaine exécution de la méthode flush.
     * 
     * La méthode flush est utilisée pour exécuter les requêtes SQL qui ont été créées par les méthodes persist, remove ou toute autre opération de modification de la base de données effectuée par Doctrine. Cette méthode exécute toutes les requêtes en attente dans l'ordre dans lequel elles ont été enregistrées et met à jour la base de données.
     * 
     * En résumé, la méthode persist est utilisée pour enregistrer une entité dans le contexte de persistance de Doctrine, et la méthode flush est utilisée pour synchroniser les changements avec la base de données en exécutant toutes les requêtes SQL en attente. Il est important de noter que les modifications ne seront pas persistées dans la base de données tant que flush n'aura pas été appelé. */

    //* details
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
