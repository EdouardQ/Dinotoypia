<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Order;
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

    /**
     * @param Order $order
     * @return \Stripe\Checkout\Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSession(Order $order): \Stripe\Checkout\Session
    {
        $MY_DOMAIN = "https://".$_SERVER['HTTP_HOST'];

        $sessionStripe = $this->stripe->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $MY_DOMAIN.'/payment/payment-succeeded',
            'cancel_url' => $MY_DOMAIN.'/payment/payment-failed',
            'customer' => $order->getCustomer()->getStripeId(),
            'line_items' => $order->getStripeLineItems(),
            'payment_method_types' => ['card']
        ]);

        return $sessionStripe;
    }
}
