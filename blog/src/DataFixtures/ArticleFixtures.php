<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitle('Soupe à la courgette')
                    ->setContent('<p>Contenu de mon article</p>')
                    ->setImage('https://via.placeholder.com/350x150')
                    ->setCreatedAt(new \DateTime());

            $manager->persist($article);


        }

        $manager->flush();
    }
}
