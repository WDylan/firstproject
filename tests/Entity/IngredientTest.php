<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Ingredient;
use App\Entity\User;

final class IngredientTest extends TestCase
{
    public function testGetName(): void
    {
        $ingredient = new Ingredient();
        $ingredient->setName('Tomate');

        $this->assertEquals('Tomate', $ingredient->getName());
    }

    public function testGetPrice(): void
    {
        $ingredient = new Ingredient();
        $ingredient->setPrice(2.5);

        $this->assertEquals(2.5, $ingredient->getPrice());
    }

    public function testGetCreateDat(): void
    {
        $ingredient = new Ingredient();
        $createDat = new \DateTimeImmutable();
        $ingredient->setCreateDat($createDat);

        $this->assertEquals($createDat, $ingredient->getCreateDat());
    }

    public function testToString(): void
    {
        $ingredient = new Ingredient();
        $ingredient->setName('Tomate');

        $this->assertEquals('Tomate', $ingredient->__toString());
    }

    public function testGetUser(): void
    {
        $ingredient = new Ingredient();
        $user = new User();
        $ingredient->setUser($user);

        $this->assertSame($user, $ingredient->getUser());
    }
}
