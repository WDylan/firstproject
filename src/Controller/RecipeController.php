<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RecipeController extends AbstractController
{
    /**
     * This function display all ingredients
     *
     * @param RecipeRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/recette', name: 'app_recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }
        $recipes = $paginator->paginate(
            $recipeRepository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /** 
     * This controller show the form to add an Ingredient in a database
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/recette/creation', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'Recette ajoutée avec succès');
            return $this->redirectToRoute('app_recipe');
        }
        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/edition/{id}', 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(RecipeRepository $recipeRepository, int $id, Request $request, EntityManagerInterface $manager, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }
        $recipe = $recipeRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'Votre recette a été modifié avec succès !');

            return $this->redirectToRoute('app_recipe');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/suppression/{id}', 'app_recipe_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, int $id, RecipeRepository $recipeRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }
        $recipe = $recipeRepository->findOneBy(["id" => $id]);

        // Vérification si l'ingrédient existe
        if (!$recipe) {
            $this->addFlash('error', "Votre recette n'a pas été trouvée !");
        } else {
            $manager->remove($recipe);
            $manager->flush();

            $this->addFlash('success', "Votre recette a été supprimé avec succès !");
        }

        return $this->redirectToRoute('app_recipe');
    }
}
