<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CivilityFixtures extends Fixture
{
    private array $list = [
        [
            'name' => 'Monsieur',
            'code' => 'm',
        ],
        [
            'name' => 'Madame',
            'code' => 'mrs',
        ],
        [
            'name' => 'Autre',
            'code' => 'other',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->list as $civility) {
            $entity = new Civility();
            $entity->setName($civility['name'])
                ->setCode($civility['code'])
            ;

            $this->addReference($civility['code'], $entity);
            $manager->persist($entity);
        }

        $manager->flush();
    }
}