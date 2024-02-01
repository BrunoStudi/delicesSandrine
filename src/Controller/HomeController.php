<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class HomeController extends AbstractController
{
    /**
     * Page d'acceuil
     * 
     * @return Response
     */
    public function index(/*PersistenceManagerRegistry $doctrine*/)//: Response
    {
        /* Entity Manager de Symfony
        $em = $doctrine->getManager();
        // Récupérer toutes les recettes en base de données
        $articles = $em->getRepository(Article::class)->findAll();
        // Afficher toutes les recettes sur la page.*/
        return $this->render('home/index.html.twig');/*, [
            'articles' => $articles,
        ]);*/
    }

    // Page formulaire de contact.
    public function contact()
    {
        return $this->render('contact/contact.html.twig');
    }
}
