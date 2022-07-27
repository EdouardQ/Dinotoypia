<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private array $ages = [
        '1-3 ans',
        '3-5 ans',
        '6-8 ans',
        '9-11 ans',
        '12+ ans'
    ];
    private array $categories = [
        "jouets d'éveil et peluches",
        "figurines",
        "jeux de société et puzzles",
    ];

    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName("Figurine Dino")
            ->setUrlName("figurine-dino")
            ->setDescription("Figurine de dinosaure - 10 cm de hauteur")
            ->addCategory($this->getReference("figurines"))
            ->setPrice(19)
            ->setProductStripeId("prod_LXhJouJAEZvQj7")
            ->setPriceStripeId("price_1KqbumHowZnzDNfSWAFkk07V")
            ->setVisible(true)
            ->setStock(42)
            ->setReleaseDate(new \DateTime())
        ;

        $this->addReference($product->getName(), $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Flipper JurassicPark")
            ->setUrlName("flipper-jurassicpark")
            ->setDescription("Flipper Jurasic Park")
            ->addCategory($this->getReference("12+ ans"))
            ->setPrice(250)
            ->setProductStripeId("prod_Luaopl7ghHmcbb")
            ->setPriceStripeId("price_1LClckHowZnzDNfSIHuGLniK")
            ->setVisible(true)
            ->setStock(10)
            ->setReleaseDate(new \DateTime())
        ;

        $this->addReference($product->getName(), $product);
        $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 3; $i++) {
            for ($i=0; $i < 100; $i++) {
                $product = new Product;
                $product->setName($faker->name())
                    ->setUrlName($product->getName())
                    ->setDescription($faker->text())
                    ->setPrice($faker->numberBetween(1, 99))
                    ->setProductStripeId("n/a")
                    ->setPriceStripeId("n/a")
                    ->addCategory($this->getReference($this->ages[$i % sizeof($this->ages)]))
                    ->addCategory($this->getReference($this->categories[$i % sizeof($this->categories)]))
                    ->setVisible(true)
                    ->setStock($faker->numberBetween(0, 200))
                    ->setReleaseDate($faker->dateTime())
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
