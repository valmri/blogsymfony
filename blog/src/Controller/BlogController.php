<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Entity\Comment;
use App\Repository\CommentRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\ArticleType;
use App\Form\CommentType;

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

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('blog/{id}/edit', name: 'blog_edit')]
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager) {
        /* Première version
        if($request->request->count() > 0) {

            $article = new Article();
            $article->setTitle($request->request->get('title'))
                    ->setContent($request->request->get('content'))
                    ->setImage($request->request->get('image'))
                    ->setCreatedAt(new \DateTime());

            dump($article);
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        */


        if( $article === null) {
            $article = new Article();
        }

        /*
        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();
        */

        // Appel de la structure du formulaire généré avec la console
        $form = $this->createForm(ArticleType::class, $article);

        // Association des données du formulaire à la classe Article
        $form->handleRequest($request);

        //dump($article);

        if($form->isSubmitted() && $form->isValid()) {

            if($article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            // Ajout de la date actuelle à l'article
            $article->setCreatedAt(new \DateTime());

            // Envoie de l'article dans la BdD
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);

        }

        return $this->render('blog/create.html.twig', [
            'formArt' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(ArticleRepository $articleRepo, int $id){

        $monArticle = $articleRepo->find($id);

        // Création du formulaire
        $comment = new Comment();
        $formCom = $this->createForm(CommentType::class, $comment);
        //dump($formCom);



        return $this->render('blog/show.html.twig', [
            'article' => $monArticle,
            'formComment' => $formCom->createView()
        ]);
    }


}
