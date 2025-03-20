<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/article/list.html.twig', [
            'articles' => [
                [
                    'title' => 'Article 1',
                    'content' => '<strong>Article 1 content</strong>',
                ],
                [
                    'title' => 'Article 2',
                    'content' => 'Article 2 content',
                ]
            ]
        ]);
    }
}