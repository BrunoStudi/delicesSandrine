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
        $articleId = $request->request->get('article_id'); // Récupérer article_id depuis la page html.
        $contenu = $request->request->get('contenu'); // idem pour la zone text-area.
        $em = $doctrine->getManager();

        // Récupérer l'article correspondant à partir de son ID en BDD
        $article = $em->getRepository(Article::class)->find($articleId);

        //Vérifier si l'article existe en BDD.
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé pour l\'ID ' . $articleId);
        }

        $comments = new Comment();
        $comments->setContent($contenu); // Associer le contenu récupéré au dessus dans la variable comments.
        $comments->setArticle($article); // Associer comments à l'article correspondant via son ID.

        $this->saveComment($doctrine, $comments);

        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    private function completeCommentBeforeSave(Comment $comments) {
        if($comments->getPublishedAt()){
            $comments->getPublishedAt(new \DateTime());
        }
        $comments->setAuthor($this->getUser()); // Ajouter l'autheur du commentaire.
        $comments->setPublishedAt(new \DateTime()); // Ajouter la date de publication du commentaire.
    
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
        $em->persist($comments); // sauvegarder les modifications.
        $em->flush(); // et enregistrer en BDD.
    }
}
