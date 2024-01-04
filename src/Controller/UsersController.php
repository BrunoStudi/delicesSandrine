<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class UsersController extends AbstractController
{
    /**
     * Page de gestion utilisateurs
     * 
     * @return Response
     */
    #[Route('/gusers', name: 'app_gusers')]
    public function gUsers(PersistenceManagerRegistry $doctrine): Response
    {
        //Entity Manager de Symfony
        $em = $doctrine->getManager();
        //Tout les autilisateurs en base de données
        $gUsers = $em->getRepository(User::class)->findAll();
        return $this->render('gusers/guser.html.twig', [
            'Utilisateurs' => $gUsers,
        ]);
    }

}