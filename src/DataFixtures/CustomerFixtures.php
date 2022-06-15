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
        $entity = new Customer();
        $entity->setEmail("e.quilliou@3im.fr")
            ->setPassword($this->userPasswordHasher->hashPassword($entity, "azerty"))
            ->setLastName("Quilliou")
            ->setFirstName("Edouard")
            ->setAddress("40 av Barthom")
            ->setCity("Paris")
            ->setCountry("FR")
            ->setPostCode("75015")
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
                    ->setAddress($faker->address())
                    ->setCity($faker->city())
                    ->setCountry("FR")
                    ->setPostCode($faker->postcode())
                    ->setPhone($faker->phoneNumber())
                    ->setStripeId("n/a")
                    ->isVerified(true)
                ;
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
