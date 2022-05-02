<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $allProducts = $manager->getRepository(Product::class)->findAll();

        foreach ($allProducts as $product) {
            $image = new Image();
            $product = $this->getReference($product->getName());
            $image->setProduct($this->getReference($product->getName()));
            $image->setName($product->getName() . "01");
            $image->setFileName("test_image_product_120.png");

            $manager->persist($image);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}