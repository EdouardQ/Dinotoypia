<?php

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductCategoryFixtures extends Fixture
{
    private array $list = [
        '1-3',
        '3-5',
        '6-8',
        '9-11',
        '12+',
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
