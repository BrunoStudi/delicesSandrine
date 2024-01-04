<?php

namespace App\Controller;

use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    /**
     * Visualiser un article
     * 
     * @param   int     $id     Identifiant de l'article
     * 
     * @return Response
     */
    public function index(PersistenceManagerRegistry $doctrine, int $id): Response
    {
        // Entity Manager de Symfony
        $em = $doctrine->getManager();
        // On récupère l'article qui correspond à l'id passé dans l'url
        $article = $em->getRepository(Article::class)->findBy(['id' => $id]);

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Modifier / ajouter un article
     */
    public function edit(PersistenceManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, int $id=null): Response
    {
        $em = $doctrine->getManager();

        if($id) {
            $mode = 'update';   //variable qui sert à detecter si on créer ou modifie un article
            $article = $em->getRepository(Article::class)->findOneBy(['id' => $id]);
        }
        else {
            $mode = 'new';
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            
            $this->saveArticle($doctrine, $article, $mode);
            // uploader une image.
            $imageFile = $form->get('imagePlat')->getData();
            // cette condition est necessaire car imageFile n'est pas un champ obligatoire,
            // donc la condition s'applique uniquement si un fichier est chargé.
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // ceci est necessaire pour inclure le nom du fichier dans l'URL de façon sécurisé.
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                // Déplacer le ficheir dans le repertoire approprié.
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... message d'erreur personalisé pour un echec d'upload.
                }
                // mise à jour des propriétées de 'imageFile' pour enregistrer le nom de l'image.
                $article->setImage($newFilename);
                // enregistrement du chemin en base de données (pas le fichier lui-même).
                $em = $doctrine->getManager();
                $em->persist($article);
                $em->flush();
            }

            //return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
            return $this->redirectToRoute('homepage');
        }

        $parameters = array(
            'form' => $form,
            'article' => $article,
            'mode' => $mode
        );

        return $this->render('article/edit.html.twig', $parameters);
    }

    /**
     * Supprimer un article
     */
    public function remove(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        // Entity Manager de Symfony
        $em = $doctrine->getManager();
        // On récupère l'article qui correspond à l'id passé dans l'url
        $article = $em->getRepository(Article::class)->findBy(['id' => $id]) [0];

        // L'article est supprimé
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * Compléter l'article avec des informations avant enregistrement
     * 
     * @param   Article     $article
     * @param   string      $mode
     * 
     * @return Article
     */
    private function completeArticleBeforeSave(Article $article, string $mode) {
        if($article->getPublishedAt()){
            $article->getPublisedAt(new \DateTime());
        }
        $article->setAuthor($this->getUser());
        
        return $article;
    }

    /**
     * Enregistrer un article en base de données
     * 
     * @param   Article     $article
     * @param   string      $mode
     */
    private function saveArticle(PersistenceManagerRegistry $doctrine, Article $article, string $mode)
    {
        $article = $this->completeArticleBeforeSave($article, $mode);

        $em = $doctrine->getManager();
        $em->persist($article);
        $em->flush();
    }
}
