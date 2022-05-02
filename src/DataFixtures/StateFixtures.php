<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    private array $list = [
        [
            'name' => "En cours",
            'code' => 'pending'
        ],
        [
            'name' => "Paiement en cours de validation",
            'code' => 'in_payment'
        ],
        [
            'name' => "En livraison",
            'code' => 'in_delevery'
        ],
        [
            'name' => "Livré",
            'code' => 'delivered'
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->list as $state) {
            $entity = new State();
            $entity->setName($state['name'])
                ->setCode($state['code'])
            ;

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
