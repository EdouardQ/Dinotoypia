<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Product;

class StripeService
{
    private string $stripe_secret_key;
    private $stripe;

    public function __construct(string $stripe_secret_key)
    {
        $this->stripe_secret_key = $stripe_secret_key;
        $this->stripe = new \Stripe\StripeClient($this->stripe_secret_key);
    }

    public function createCustomer(Customer $entity): void
    {
        $stripeCustomer = $this->stripe->customers->create([
            'name' => $entity->getLastName() . ' ' . $entity->getFirstName(),
            'email' => $entity->getEmail(),
            'preferred_locales' => ['fr-FR']
        ]);

        $entity->setStripeId($stripeCustomer->id);
    }

    public function createProduct(Product $entity): void
    {
        $stripeProduct = $this->stripe->products->create([
            'name' => $entity->getName()
        ]);

        $stripePrice = $this->stripe->prices->create([
            'unit_amount' => $entity->getPrice()*100,
            'currency' => 'eur',
            'product' => $stripeProduct->id,
        ]);

        $entity->setProductStripeId($stripeProduct->id);
        $entity->setPriceStripeId($stripePrice->id);
    }

    public function findAllPriceFromProduct(string $productStripeId): array
    {
        return $this->stripe->prices->all([
            'active' => true,
            'product' => $productStripeId
            ])->data;
    }

    public function createPrice(Product $entity): void
    {
        $stripePrice = $this->stripe->prices->create([
            'unit_amount' => $entity->getPrice()*100,
            'currency' => 'eur',
            'product' => $entity->getProductStripeId(),
        ]);

        $entity->setPriceStripeId($stripePrice->id);
    }
}
