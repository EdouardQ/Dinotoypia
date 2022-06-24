<?php

namespace App\DataFixtures;

use App\Entity\RefurbishState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RefurbishStateFixtures extends Fixture
{
    private array $list = [
        [
            'name' => "En attente de dépôt",
            'code' => 'waiting_deposit'
        ],
        [
            'name' => "En livrason",
            'code' => 'in_delivery'
        ],
        [
            'name' => "Récupéré",
            'code' => 'retrieve'
        ],
        [
            'name' => "Reconditionner",
            'code' => 'refurbish'
        ],
        [
            'name' => "Remise en vente",
            'code' => 're-sale'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $state) {
            $entity = new RefurbishState();
            $entity->setName($state['name'])
                ->setCode($state['code'])
            ;

            // $this->addReference($state['code'], $entity);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
