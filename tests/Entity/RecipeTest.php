<?php

declare(strict_types=1);

use Phpunit\Framework\TestCase;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\User;

final class RecipeTest extends TestCase
{
    public function testGetName(): void
    {
        $recipe = new Recipe();
        $recipe->setName('Tarte aux fraises');

        $this->assertEquals('Tarte aux fraises', $recipe->getName());
    }

    public function testGetTime(): void
    {
        $recipe = new Recipe();
        $time = 45;
        $recipe->setTime($time);

        $this->assertEquals($time, $recipe->getTime());
    }

    public function testGetNbPeople(): void
    {
        $recipe = new Recipe();
        $nbPeople = 6;
        $recipe->setNbPeople($nbPeople);

        $this->assertEquals($nbPeople, $recipe->getNbPeople());
    }

    public function testGetDifficulty(): void
    {
        $recipe = new Recipe();
        $difficulty = 2;
        $recipe->setDifficulty($difficulty);

        $this->assertEquals($difficulty, $recipe->getDifficulty());
    }

    public function testGetDescription(): void
    {
        $recipe = new Recipe();
        $description = "Description de la recette";
        $recipe->setDescription($description);

        $this->assertEquals($description, $recipe->getDescription());
    }

    public function testGetPrice(): void
    {
        $recipe = new Recipe();
        $price = 12.99;
        $recipe->setPrice($price);

        $this->assertEquals($price, $recipe->getPrice());
    }

    public function testIsFavorite(): void
    {
        $recipe = new Recipe();
        $isFavorite = true;
        $recipe->setFavorite($isFavorite);

        $this->assertEquals($isFavorite, $recipe->isFavorite());
    }

    public function testGetCreateDat(): void
    {
        $recipe = new Ingredient();
        $createDat = new \DateTimeImmutable();
        $recipe->setCreateDat($createDat);

        $this->assertEquals($createDat, $recipe->getCreateDat());
    }

    public function testSetUpdateDat(): void
    {
        $recipe = new Recipe();
        $updateDat = new \DateTimeImmutable();

        $recipe->setUpdateDat($updateDat);

        $this->assertEquals($updateDat, $recipe->getUpdateDat());
    }

    public function testGetIngredients(): void
    {
        $recipe = new Recipe();
        $ingredient = new Ingredient();
        $recipe->addIngredient($ingredient);

        $this->assertTrue($recipe->getIngredients()->contains($ingredient));
    }

    public function testAddIngredient(): void
    {
        $recipe = new Recipe();
        $ingredient = new Ingredient();

        $recipe->addIngredient($ingredient);

        $this->assertTrue($recipe->getIngredients()->contains($ingredient));
    }

    public function testRemoveIngredient(): void
    {
        $recipe = new Recipe();
        $ingredient = new Ingredient();
        $recipe->addIngredient($ingredient);

        $recipe->removeIngredient($ingredient);

        $this->assertFalse($recipe->getIngredients()->contains($ingredient));
    }

    public function testGetUser(): void
    {
        $recipe = new Recipe();
        $user = new User();
        $recipe->setUser($user);

        $this->assertEquals($user, $recipe->getUser());
    }
}
