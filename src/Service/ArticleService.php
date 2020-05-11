<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleRepository;
use App\Form\Articles\CreateView;

class ArticleService
{
    private $articles;
    private $flusher;

    public function __construct(ArticleRepository $articles, Flusher $flusher)
    {
        $this->articles = $articles;
        $this->flusher = $flusher;
    }

    public function create(CreateView $form)
    {
        $article = new Article(
            new \DateTimeImmutable(),
            $form->title,
            $form->content
        );
        $this->articles->add($article);
        $this->flusher->flush();
        return $article;

    }
}