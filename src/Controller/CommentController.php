<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Entity\Article;

class CommentController extends AbstractController
{
    //#[Route('/recette/{articleId}/commentaire/ajout', name: 'commentaire_ajout', methods: 'POST')]
    public function ajout(PersistenceManagerRegistry $doctrine, Request $request, Article $article): Response
    {
        $contenu = $request->request->get('contenu');

        $commentaire = new Comment();
        $commentaire->setContent($contenu);
        //$commentaire->setArticle($article);

        $em = $doctrine->getManager();
        $this->saveComment($doctrine, $commentaire);

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    private function completeCommentBeforeSave(Comment $commentaire) {
        if($commentaire->getPublishedAt()){
            $commentaire->getPublishedAt(new \DateTime());
        }
        $commentaire->setAuthor($this->getUser());
        $commentaire->setPublishedAt(new \DateTime());
    
        return $commentaire;
    }

    /**
     * Enregistrer un commentaire en base de données
     * 
     * @param   Comment     $commentaire
     */
    private function saveComment(PersistenceManagerRegistry $doctrine, Comment $commentaire)
    {
        $article = $this->completeCommentBeforeSave($commentaire);

        $em = $doctrine->getManager();
        $em->persist($commentaire);
        $em->flush();
    }
}
