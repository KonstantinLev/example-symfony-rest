<?php

namespace App\Controller;

use App\Entity\ArticleRepository;
use App\Form\Articles\CreateView;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/articles", name="articles", methods={"GET", "HEAD"})
     *
     * @param ArticleRepository $articles
     * @return Response
     */
    public function getArticles(ArticleRepository $articles): Response
    {
        $data = $articles->repo->findAll();
        //$json = $this->serializer->serialize($data, 'json');
        return $this->json($data, Response::HTTP_OK);
    }

    /**
     * @Route("/articles", name="articles.create", methods={"POST"})
     *
     * @param Request $request
     * @param ArticleService $service
     * @return Response
     */
    public function postArticle(Request $request, ArticleService $service): Response
    {
        $data = json_decode($request->getContent(), true);

        $form = new CreateView();
        $form->title = $data['title'] ?? null;
        $form->content = $data['content'] ?? null;

        $violations = $this->validator->validate($form);
        if (\count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');
            //return new JsonResponse($json, 400, [], true);
            return $this->json($json, 400);
        }

        $article = $service->create($form);
        //$json = $this->serializer->serialize($data, 'json');
        return $this->json($article, Response::HTTP_CREATED);
    }
}