<?php

namespace App\DataFixtures;

use App\Entity\ToyCondition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToyConditionFixtures extends Fixture
{
    private array $list = [
        [
            'name' => "Neuf",
            'code' => 'unused'
        ],
        [
            'name' => "Très bon",
            'code' => 'very_good'
        ],
        [
            'name' => "Bon",
            'code' => 'good'
        ],
        [
            'name' => "Satisfaisant",
            'code' => 'satisfactory'
        ],
        [
            'name' => "Mauvais",
            'code' => 'bad'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $condition) {
            $entity = new ToyCondition();
            $entity->setName($condition['name'])
                ->setCode($condition['code'])
            ;

            // $this->addReference($condition['code'], $entity);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
