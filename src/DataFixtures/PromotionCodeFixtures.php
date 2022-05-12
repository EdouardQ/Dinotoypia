<?php

namespace App\DataFixtures;

use App\Entity\PromotionCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PromotionCodeFixtures extends Fixture
{
    private array $list = [
        [
            'name' => "Remise sur premier achat",
            'code' => 'DINO10',
            'type' => 'giftcode',
            'stripeId' => 'promo_1Kxd9OHowZnzDNfSk5DqZUxL',
            'couponStrideId' => 'GCLK4LYv',
            'amount' => '10',
            'amountType' => 'amount',
            'expiresAt' => null,
            'comments' => "Remise de 10€ sur le premier achat à partir de 20€ de commande."
        ],
        [
            'name' => "Offre Printemps - Été",
            'code' => 'DINO20',
            'type' => 'giftcode',
            'stripeId' => 'promo_1KxdRUHowZnzDNfS4NM6oJ07',
            'couponStrideId' => 'AdvDy1qK',
            'amount' => '20',
            'amountType' => 'percentage',
            'expiresAt' => "2022-08-31 23:59",
            'comments' => "Remise de 20% à partir de 20€."
        ],

    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->list as $promotionCode) {
            $entity = new PromotionCode();
            $entity->setName($promotionCode['name'])
                ->setCode($promotionCode['code'])
                ->setType($promotionCode['type'])
                ->setStripeId($promotionCode['stripeId'])
                ->setCouponStripeId($promotionCode['couponStrideId'])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setAmount($promotionCode['amount'])
                ->setAmountType($promotionCode['amountType'])
                ->setComments($promotionCode['comments'])
            ;

            $promotionCode['expiresAt'] ? $entity->setExpiresAt(new \DateTimeImmutable($promotionCode['expiresAt'])) : $entity->setExpiresAt(null);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
