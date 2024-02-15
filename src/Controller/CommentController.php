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
    public function ajout(PersistenceManagerRegistry $doctrine, Request $request): Response
    {
        $articleId = $request->request->get('article_id');
        $contenu = $request->request->get('contenu');
        $em = $doctrine->getManager();

        // Récupérer l'article correspondant à partir de son ID
        $article = $em->getRepository(Article::class)->find($articleId);

        //Vérifier si l'article existe en BDD.
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé pour l\'ID ' . $articleId);
        }

        $comments = new Comment();
        $comments->setContent($contenu);
        $comments->setArticle($article);

        $this->saveComment($doctrine, $comments);

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    private function completeCommentBeforeSave(Comment $comments) {
        if($comments->getPublishedAt()){
            $comments->setPublishedAt(new \DateTime());
        }
        $comments->setAuthor($this->getUser());
    
        return $comments;
    }

    /**
     * Enregistrer un commentaire en base de données
     * 
     * @param   Comment     $comments
     */
    private function saveComment(PersistenceManagerRegistry $doctrine, Comment $comments)
    {
        $comments = $this->completeCommentBeforeSave($comments);

        $em = $doctrine->getManager();
        $em->persist($comments);
        $em->flush();
    }
}
