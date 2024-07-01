<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('pages/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('deconnexion', name: 'app_logout')]
    public function logout()
    {
        // Controller can be blank: it will never be called
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->addFlash('success', 'Votre compte a bien été créé');

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/login/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}