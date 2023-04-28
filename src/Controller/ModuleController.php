<?php

namespace App\Controller;

use App\Entity\Module;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(  ManagerRegistry $doctrine ): Response
    {
        $modules = $doctrine->getRepository( Module::class)->findBy([], ["title" => "ASC"]);

        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }
}
