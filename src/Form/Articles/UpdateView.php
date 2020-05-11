<?php

namespace App\Form\Articles;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateView
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;
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

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}