<?php

namespace App\Controller;

use App\Entity\ArticleRepository;
use App\Form\Articles\CreateView;
use App\Form\Articles\UpdateView;
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

    private $response = [
        'errors' => null,
        'data' => null
    ];

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
        $this->response['data'] = $articles->repo->findAll();
        //$json = $this->serializer->serialize($data, 'json');
        return $this->json($this->response, Response::HTTP_OK);
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
            $this->response['errors'] = $json;
            return $this->json($this->response, 400);
        }

        $article = $service->create($form);
        $this->response['data'] = $article;
        //$json = $this->serializer->serialize($data, 'json');
        return $this->json($this->response, Response::HTTP_CREATED);
    }

    /**
     * @Route("/articles/{id}", name="articles.update", methods={"PUT", "PATCH"})
     *
     * @param int $id
     * @param Request $request
     * @param ArticleRepository $articles
     * @param ArticleService $service
     * @return Response
     */
    public function putArticle(int $id, Request $request, ArticleRepository $articles, ArticleService $service): Response
    {
        $article = $articles->repo->find($id);
        if (!$article) {
            $this->response['errors'] = 'Article with id '.$id.' does not exist!';
            return $this->json($this->response, Response::HTTP_NOT_FOUND);
            //throw new EntityNotFoundException('Article with id '.$id.' does not exist!');
        }

        $data = json_decode($request->getContent(), true);

        $form = new UpdateView($article->getId());
        $form->title = $data['title'] ?? null;
        $form->content = $data['content'] ?? null;

        $violations = $this->validator->validate($form);
        if (\count($violations)) {
            $this->response['errors'] = $this->serializer->serialize($violations, 'json');
            //return new JsonResponse($json, 400, [], true);
            return $this->json($this->response, 400);
        }

        try {
            $article = $service->update($form);
        } catch (\Exception $e) {
            return $this->json($this->response, Response::HTTP_BAD_REQUEST);
        }

        $this->response['data'] = $article;
        //$json = $this->serializer->serialize($data, 'json');
        return $this->json($this->response, Response::HTTP_OK);
    }
}