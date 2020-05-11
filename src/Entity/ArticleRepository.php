<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ArticleRepository
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    public $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Article::class);
    }

    public function get($id): Article
    {
        /** @var Article $article */
        if (!$article = $this->repo->find($id)) {
            throw new EntityNotFoundException('Article is not found.');
        }
        return $article;
    }

    public function add(Article $article): void
    {
        $this->em->persist($article);
    }

    public function remove(Article $article): void
    {
        $this->em->remove($article);
    }
}