<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\GiftCode;
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
            'payment_method_types' => ['card']
        ]);

        return $sessionStripe;
    }

    public function createGiftCode(GiftCode $giftCode): void
    {
        $today = new \DateTime();
        if ($today > $giftCode->getExpiresAt()) {
            throw new \Exception("Invalid Expires At");
        }

        $duration_in_months = $today->diff($giftCode->getExpiresAt())->m;

        if ($giftCode->getType() === 'percentage') {
            if (floatval($giftCode->getAmount()) < 0 || floatval($giftCode->getAmount()) >= 100) {
                throw new \Exception("Invalid Percentage");
            }
            $coupon = $this->stripe->coupons->create([
                'name' => $giftCode->getName(),
                'percent_off' => $giftCode->getAmount(),
                'duration' => 'repeating',
                'duration_in_months' => $duration_in_months
            ]);
        }
        elseif ($giftCode->getType() === 'amount') {
            if (floatval($giftCode->getAmount()) < 0) {
                    throw new \Exception("Invalid Amount");
            }
            $coupon = $this->stripe->coupons->create([
                'name' => $giftCode->getName(),
                'amount_off' => $giftCode->getAmount()*100,
                'currency' => 'EUR',
                'duration' => 'repeating',
                'duration_in_months' => $duration_in_months
            ]);
        }

        $giftCode->setCouponStripeId($coupon->id);

        $promotionCode = $this->stripe->promotionCodes->create([
            'coupon' => $coupon->id,
            'code' => $giftCode->getCode(),
            'expires_at' => $giftCode->getExpiresAt()->format('U')
        ]);

        $giftCode->setGiftCodeStripeId($promotionCode->id);
    }
}
