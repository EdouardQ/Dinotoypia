<?php

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductCategoryFixtures extends Fixture
{
    private array $list = [
        '1-3 ans',
        '3-5 ans',
        '6-8 ans',
        '9-11 ans',
        '12+ ans',
        "jouets d'éveil et peluches",
        "figurines",
        "jeux de société et puzzles",
    ];
    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $category) {
            $entity = new ProductCategory();
            $entity->setName($category);

            $this->addReference($category, $entity);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
