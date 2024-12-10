<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le mot de passe uniquement si fourni
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('app_user_index');
    }

    // #[Route('/profile', name: 'app_user_profile', methods: ['GET', 'POST'])]
    // public function profile(
    //     Request $request, 
    //     EntityManagerInterface $entityManager, 
    //     UserPasswordHasherInterface $passwordHasher
    // ): Response {
    //     // Récupérer l'utilisateur connecté
    //     $user = $this->getUser();
    //     if (!$user) {
    //         $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
    //         return $this->redirectToRoute('app_login');
    //     }
    
    //     // Créer le formulaire
    //     $form = $this->createForm(UserType::class, $user, [
    //         'disabled_roles' => true, // Option pour griser le champ des rôles
    //     ]);
    //     $form->handleRequest($request);
    
    //     // Traitement du formulaire
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Hachage du mot de passe si modifié
    //         if ($form->get('plainPassword')->getData()) {
    //             $user->setPassword(
    //                 $passwordHasher->hashPassword(
    //                     $user, // L'utilisateur doit implémenter PasswordAuthenticatedUserInterface
    //                     $form->get('plainPassword')->getData() // Le mot de passe doit être une chaîne valide
    //                 )
    //             );
    //         }
    
    //         $entityManager->flush();
    
    //         $this->addFlash('success', 'Profil mis à jour avec succès.');
    //         return $this->redirectToRoute('app_user_profile');
    //     }
    
    //     return $this->render('user/profile.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}

