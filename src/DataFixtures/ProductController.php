<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductController extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 3; $i++) {
            $category = new ProductCategory;
            $category->setLabel($faker->colorName());

            $manager->persist($category);
        
            for ($i=0; $i < 30; $i++) {
                $product = new Product;
                $product->setLabel($faker->name())
                    ->setDescription($faker->text())
                    ->setPrice($faker->numberBetween(2, 50))
                    ->setCategory($category)
                ;
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
