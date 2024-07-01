<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Ingredient;
use App\Entity\User;
use App\Entity\Recipe;


final class UserTest extends TestCase
{
    public function testGetEmail()
    {
        $user = new User();
        $user->setEmail('email@email.fr');

        $this->assertEquals('email@email.fr', $user->getEmail());
    }

    public function testGetUserIdentifier()
    {
        $user = new User();
        $user->setEmail('admin@admin.com');

        $this->assertEquals('admin@admin.com', $user->getUserIdentifier());
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testGetPassword()
    {
        $user = new User();
        $user->setPassword('password');

        $this->assertEquals('password', $user->getPassword());
    }

    public function testGetName()
    {
        $user = new User();
        $user->setName('John');

        $this->assertEquals('John', $user->getName());
    }

    public function testGetPseudo()
    {
        $user = new User();
        $user->setPseudo('Johnny');

        $this->assertEquals('Johnny', $user->getPseudo());
    }

    public function testGetPlainPassword()
    {
        $user = new User();
        $user->setPlainPassword('password');

        $this->assertEquals('password', $user->getPlainPassword());
    }

    public function testGetCreateDat(): void
    {
        $user = new User();
        $createDat = new \DateTimeImmutable();
        $user->setCreateDat($createDat);

        $this->assertEquals($createDat, $user->getCreateDat());
    }

    public function testGetIngredients(): void
    {
        $user = new User();
        $ingredient = new Ingredient();
        $user->addIngredient($ingredient);

        $this->assertTrue($user->getIngredients()->contains($ingredient));
    }

    public function testAddIngredient(): void
    {
        $user = new User();
        $ingredient = new Ingredient();
        $user->addIngredient($ingredient);

        $this->assertTrue($user->getIngredients()->contains($ingredient));
    }

    public function testRemoveIngredient(): void
    {
        $user = new User();
        $ingredient = new Ingredient();
        $user->addIngredient($ingredient);
        $user->removeIngredient($ingredient);

        $this->assertFalse($user->getIngredients()->contains($ingredient));
    }

    public function testGetRecipe(): void
    {
        $user = new User();
        $recipe = new Recipe();
        $user->addRecipe($recipe);

        $this->assertTrue($user->getRecipes()->contains($recipe));
    }

    public function testAddRecipe(): void
    {
        $user = new User();
        $recipe = new Recipe();
        $user->addRecipe($recipe);

        $this->assertTrue($user->getRecipes()->contains($recipe));
    }

    public function testRemoveRecipe(): void
    {
        $user = new User();
        $recipe = new Recipe();
        $user->addRecipe($recipe);
        $user->removeRecipe($recipe);

        $this->assertFalse($user->getRecipes()->contains($recipe));
    }
}
