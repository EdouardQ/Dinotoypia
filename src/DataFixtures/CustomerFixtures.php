<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 4; $i++) { 
            $entity = new Customer;
            $entity->setEmail($faker->email())
                    ->setLastName($faker->lastName())
                    ->setFirstName($faker->firstName())
                    ->setAddress($faker->address())
                    ->setCity($faker->city())
                    ->setCountry($faker->country())
                    ->setZip($faker->postcode())
                    ->setPhone($faker->phoneNumber())
                    ->setFidelityPoints($faker->numberBetween(0, 2000))
                    ->isVerified(true)
                ;
            $password = $this->userPasswordHasher->hashPassword($entity, "azerty");
            $entity->setPassword($password);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
