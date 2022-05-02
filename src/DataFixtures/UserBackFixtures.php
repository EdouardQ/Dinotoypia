<?php

namespace App\DataFixtures;

use App\Entity\UserBack;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBackFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private array $list = [
        [
            'email' => "e.quilliou@dino.fr",
            'firstName' => "Edouard",
            'lastName' => "Quilliou",
        ],
        [
            'email' => "m.baribaud@dino.fr",
            'firstName' => "Marc",
            'lastName' => "Baribaud",
        ],
        [
            'email' => "j.groetschel@dino.fr",
            'firstName' => "Jonas",
            'lastName' => "groetschel",
        ],
    ];

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $userBack) {
            $entity = new UserBack();
            $entity->setEmail($userBack['email']);
            $entity->setFirstName($userBack['firstName']);
            $entity->setLastName($userBack['lastName']);
            $entity->setRoles(["ROLE_ADMIN", "ROLE_DEV"]);
            $entity->setCreatedAt(new \DateTimeImmutable());
            $entity->setPassword($this->userPasswordHasher->hashPassword($entity, "azerty"));

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
