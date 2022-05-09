<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Image;
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
    
    // Payment Part

    /**
     * @param Order $order
     * @return \Stripe\Checkout\Session
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createSession(Order $order): \Stripe\Checkout\Session
    {
        $domain = "https://".$_SERVER['HTTP_HOST'];

        $sessionStripe = $this->stripe->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $domain.'/payment/payment-succeeded',
            'cancel_url' => $domain.'/payment/payment-failed',
            'customer' => $order->getCustomer()->getStripeId(),
            'line_items' => $order->getStripeLineItems(),
            'payment_method_types' => ['card'],
            'shipping_options' => [
                ['shipping_rate' => 'shr_1KxX47HowZnzDNfSI0w3dtMP']
            ],
            'discounts' => [
                'promotion_code' => $order->getPromotionCode()->getStripeId()
            ]
        ]);

        return $sessionStripe;
    }
    
    // Entity Part

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
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
        ]);

        $stripePrice = $this->stripe->prices->create([
            'unit_amount' => $entity->getPrice()*100,
            'currency' => 'eur',
            'product' => $stripeProduct->id,
        ]);

        $entity->setProductStripeId($stripeProduct->id);
        $entity->setPriceStripeId($stripePrice->id);
    }

    public function updateProduct(Product $entity): void
    {
        $this->stripe->products->update($entity->getProductStripeId(), [
            'name' => $entity->getName(),
            'description' => $entity->getDescription()
        ]);
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

    public function updateImageToStripeProduct(Image $entity): void
    {
        $product = $entity->getProduct();
        $domain = "https://".$_SERVER['HTTP_HOST'];

        $this->stripe->products->update($product->getProductStripeId(), [
            'images' => [
                $domain.'/img/products/'.$entity->getFileName()
            ]
        ]);
    }
}
