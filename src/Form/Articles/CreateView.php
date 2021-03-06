<?php

namespace App\Form\Articles;

use Symfony\Component\Validator\Constraints as Assert;

class CreateView
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $title;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $content;
}