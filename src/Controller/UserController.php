<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserController extends AbstractController
{
    /**
     * This controller allow to update the name and the pseudo of user
     * 
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/utilisateur/edition/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $userRepository, int $id, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }

        $user = $userRepository->findOneBy(["id" => $id]);

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            // Redirige vers une page d'erreur ou effectuer une autre action appropriée
            return $this->redirectToRoute('error_page');
        }
        // verif si le user est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // verif si le user connecté est le même que nous avons récupéré
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_recipe');
        }

        // Création du formulaire
        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                $user = $form->getData();

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre profil a bien été modifié !');

                return $this->redirectToRoute('app_recipe');
            } else {
                $this->addFlash('warning', 'Le mot de passe est incorrect !');
            }
        }
        return $this->render('/pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('utilisateur/edition-mot-de-passe/{id}', name: 'user_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(UserRepository $userRepository, int $id, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }
        
        // Récupération de l'utilisateur par son $id
        $user = $userRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié !'
                );
                return $this->redirectToRoute('app_recipe');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect !'
                );
            }
        }
        return $this->render('pages/user/edit_Password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}