<?php

namespace App\DataFixtures;

use App\Entity\ContactSubject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactSubjectFixtures extends Fixture
{
    private array $list = [
        [
            'subject' => "Commandes / Livraison"
        ],
        [
            'subject' => "Reconditionnement"
        ],
        [
            'subject' => "Mes infos / Mon compte"
        ],
        [
            'subject' => "Autre"
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $subject) {
            $entity = new ContactSubject();
            $entity->setSubject($subject['subject']);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
