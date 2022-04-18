<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private array $ages = [
        '1-3',
        '3-5',
        '6-8',
        '9-11',
        '12+'
    ];
    private array $categories = [
        "jouets d'éveil et peluches",
        "figurines",
        "jeux de société et puzzles",
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 3; $i++) {

            for ($i=0; $i < 100; $i++) {
                $product = new Product;
                $product->setName($faker->name())
                    ->setUrlName($product->getName())
                    ->setDescription($faker->text())
                    ->setPrice($faker->numberBetween(1, 99))
                    ->setStripeId("n/a")
                    ->addCategory($this->getReference($this->ages[$i % sizeof($this->ages)]))
                    ->addCategory($this->getReference($this->categories[$i % sizeof($this->categories)]))
                ;

                $this->addReference($product->getName(), $product);
                $manager->persist($product);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductCategoryFixtures::class,
        ];
    }
}
