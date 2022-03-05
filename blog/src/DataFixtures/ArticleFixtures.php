<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\DataFixtures\Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création de catégories
        for ($i = 0; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Création d'articles
            for($j = 1; $j <= mt_rand(4,6); $j++) {
                $article = new Article();

                $content = '<p>';
                $content .= $faker->paragraphs(5, '</p><p>');
                $content .= '</p>';

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl(276, 144, 'food'))
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($article);

                // Création de commentaires
                for ($k = 0; $k <= mt_rand(4,10); $k++) {
                    $comment = new Comment();

                    $content = '<p>';
                    $content .= $faker->paragraphs(1, '</p><p>');
                    $content .= '</p>';

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = "-" . $days . "days";

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }

            }

        }

        $manager->flush();

    }
}
