<?php

namespace App\DataFixtures;

use App\Entity\Shipping;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShippingFixtures extends Fixture
{
    private array $list = [
        [
            'name' => 'Livraison Colissimo',
            'fee' => 5,
            'active' => true,
            'deliveryEstimateMinimum' => 3,
            'deliveryEstimateMaximum' => 5,
            'stripeId' => 'shr_1KyFsbHowZnzDNfSaRA5opCL'
        ],
        [
            'name' => 'Livraison Chronopost',
            'fee' => 10,
            'active' => true,
            'deliveryEstimateMinimum' => 1,
            'deliveryEstimateMaximum' => 2,
            'stripeId' => 'shr_1KyFryHowZnzDNfSPcoxp7OX'
        ],
        [
            'name' => 'Livraison Mondial Relais',
            'fee' => 5,
            'active' => false,
            'deliveryEstimateMinimum' => 3,
            'deliveryEstimateMaximum' => 5,
            'stripeId' => 'shr_1KxX47HowZnzDNfSI0w3dtMP'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $shipping) {
            $entity = new Shipping();
            $entity->setName($shipping['name'])
                ->setFee($shipping['fee'])
                ->setActive($shipping['active'])
                ->setDeliveryEstimateMinimum($shipping['deliveryEstimateMinimum'])
                ->setDeliveryEstimateMaximum($shipping['deliveryEstimateMaximum'])
                ->setStripeId($shipping['stripeId'])
            ;

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
