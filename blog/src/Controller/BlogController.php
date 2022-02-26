<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $articleRepo): Response
    {

        $articles = $articleRepo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    #[Route('/', name: 'home')]
    public function home() {
        return $this->render('blog/home.html.twig', [
            'Prenom' => "Valentin"
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(ArticleRepository $articleRepo, int $id){

        $monArticle = $articleRepo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $monArticle
        ]);
    }
}
