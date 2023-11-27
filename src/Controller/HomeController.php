<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class HomeController extends AbstractController
{
    /**
     * Page d'acceuil
     * 
     * @return Response
     */
    public function index(): Response
    {
        //Entity Manager de Symfony
        $em = $this->getDoctrine()->getManager();
        //Tout les articles en base de données
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
