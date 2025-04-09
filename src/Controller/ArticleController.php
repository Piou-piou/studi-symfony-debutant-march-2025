<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Form\FormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('pages/article/list.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(FormHandler $formHandler): Response
    {
        return $formHandler->handle(ArticleType::class, $this->generateUrl('article_index'), 'pages/article/create.html.twig');
    }


    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(FormHandler $formHandler, Article $article): Response
    {
        return $formHandler->handle(ArticleType::class, $this->generateUrl('article_index'), 'pages/article/edit.html.twig', $article);
    }
}