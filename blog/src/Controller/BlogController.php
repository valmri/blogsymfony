<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
    public function create(Request $request, EntityManagerInterface $manager) {
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
        $article = new Article();

        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                         'attr' => [
                             'placeholder' => "Titre de l'article",
                             'class' => "form-control"
                         ]
                     ])
                     ->add('content', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article",
                             'class' => "form-control"
                         ]
                     ])
                     ->add('image', TextType::class, [
                         'attr' => [
                             'placeholder' => "Lien de l'image",
                             'class' => "form-control"
                         ]
                     ])
                     ->getForm();


        return $this->render('blog/create.html.twig', [
            'formArt' => $form->createView()
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
