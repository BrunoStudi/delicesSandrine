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

        $comments = new Comment();
        $comments->setContent($contenu);
        $comments->setArticle($article);

        $em = $doctrine->getManager();
        $this->saveComment($doctrine, $comments, $article);

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    private function completeCommentBeforeSave(Comment $comments) {
        if($comments->getPublishedAt()){
            $comments->getPublishedAt(new \DateTime());
            $comments->setPublishedAt(new \DateTime());
        }
        $comments->setAuthor($this->getUser());
        $comments->setPublishedAt(new \DateTime());
    
        return $comments;
    }

    /**
     * Enregistrer un commentaire en base de données
     * 
     * @param   Comment     $comments
     */
    private function saveComment(PersistenceManagerRegistry $doctrine, Comment $comments, Article $article)
    {
        $comments = $this->completeCommentBeforeSave($comments);

        $em = $doctrine->getManager();
        $em->persist($article); // Persister l'entité Article
        $em->persist($comments);
        $em->flush();
    }
}
