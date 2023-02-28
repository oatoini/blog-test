<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }
}
