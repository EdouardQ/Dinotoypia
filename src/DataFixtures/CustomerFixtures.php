<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $entity = new Customer();
        $entity->setEmail("e.quilliou@3im.fr")
            ->setPassword($this->userPasswordHasher->hashPassword($entity, "azerty"))
            ->setLastName("Quilliou")
            ->setFirstName("Edouard")
            ->setCivility($this->getReference('m'))
            ->setPhone("0777065063")
            ->setStripeId("cus_LXiBlG0MTCgmKZ")
            ->isVerified(true)
        ;
        $manager->persist($entity);

        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 4; $i++) { 
            $entity = new Customer;
            $entity->setEmail($faker->email())
                    ->setPassword($this->userPasswordHasher->hashPassword($entity, "azerty"))
                    ->setLastName($faker->lastName())
                    ->setFirstName($faker->firstName())
                    ->setCivility($this->getReference($faker->randomElement(['m', 'mrs', 'other'])))
                    ->setPhone($faker->phoneNumber())
                    ->setStripeId("n/a")
                    ->isVerified(true)
                ;
            $manager->persist($entity);
        }
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            CivilityFixtures::class,
        ];
    }
}
