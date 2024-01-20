<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class AddController extends AbstractController
{
    // Ajouter un utilisateur via administration.
    #[Route('/adduser', name: 'app_add')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, 
    UserAuthenticatorInterface $userAuthenticator, PersistenceManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $user->setUsername($form->get('username')->getData());
           
            // hasher le mot de pass.
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Enregistrement en BDD.
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_gusers');
        }

        // Création de la vue pour ajouter l'utilisateur.
        return $this->render('registration/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
