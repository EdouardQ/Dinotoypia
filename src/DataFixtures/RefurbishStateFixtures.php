<?php

namespace App\DataFixtures;

use App\Entity\RefurbishState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RefurbishStateFixtures extends Fixture
{
    private array $list = [
        [
            'name' => "En évaluation",
            'code' => 'in_evaluation'
        ],
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
            'name' => "Reconditionnement",
            'code' => 'reconditioning'
        ],
        [
            'name' => "Remise en vente",
            'code' => 're-sale'
        ],
        [
            'name' => "Vendu",
            'code' => 'sold'
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
