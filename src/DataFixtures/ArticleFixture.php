<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $article1 = new Article(
            new \DateTimeImmutable(),
            'First Article',
            'First Content'
        );

        $manager->persist($article1);

        $article2 = new Article(
            new \DateTimeImmutable(),
            'Second Article',
            'Second Content'
        );

        $manager->persist($article2);

        $article3 = new Article(
            new \DateTimeImmutable(),
            'Third Article',
            'Third Content'
        );

        $manager->persist($article3);

        $manager->flush();
    }
}