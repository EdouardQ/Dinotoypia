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
            'email' => "e.quilliou@dinotoypia.fr",
            'firstName' => "Edouard",
            'lastName' => "Quilliou",
        ],
        [
            'email' => "m.baribaud@dinotoypia.fr",
            'firstName' => "Marc",
            'lastName' => "Baribaud",
        ],
        [
            'email' => "j.Groetschel@dinotoypia.fr",
            'firstName' => "Jonas",
            'lastName' => "Groetschel",
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
            $entity->setRoles(["ROLE_USERBACK"]);
            $entity->setCreatedAt(new \DateTimeImmutable());
            $entity->setPassword($this->userPasswordHasher->hashPassword($entity, "azerty"));

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
