<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index( ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository( Category::class)->findBy([], ["title" => "ASC"]);
        
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
