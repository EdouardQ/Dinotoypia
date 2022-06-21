<?php

namespace App\Service;

use App\Entity\BillingAddress;
use App\Entity\Customer;
use App\Entity\DeliveryAddress;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PromotionCode;
use App\Entity\Shipping;
use App\Entity\State;
use Doctrine\ORM\EntityManagerInterface;

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
        $expireAt = 3600;


        if ($order->getPromotionCode()) {
            $sessionStripe = $this->stripe->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => $domain.'/payment/succeeded-payment',
                'cancel_url' => $domain.'/payment/failed-payment',
                'expires_at' => time()+$expireAt,
                'customer' => $order->getCustomer()->getStripeId(),
                'line_items' => $order->getStripeLineItems(),
                'payment_method_types' => ['card'],
                'shipping_options' => [
                    ['shipping_rate' => $order->getShipping()->getStripeId()]
                ],
                'billing_address_collection' => 'required',
                'shipping_address_collection' => ['allowed_countries' => ['FR']],
                'discounts' => [
                    ['promotion_code' => $order->getPromotionCode()->getStripeId()]
                ]
            ]);

            return $sessionStripe;
        }

        $sessionStripe = $this->stripe->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $domain.'/payment/succeeded-payment',
            'cancel_url' => $domain.'/payment/failed-payment',
            'expires_at' => time()+$expireAt,
            'customer' => $order->getCustomer()->getStripeId(),
            'line_items' => $order->getStripeLineItems(),
            'payment_method_types' => ['card'],
            'shipping_options' => [
                ['shipping_rate' => $order->getShipping()->getStripeId()]
            ],
            'billing_address_collection' => 'required',
            'shipping_address_collection' => ['allowed_countries' => ['FR']],
        ]);

        return $sessionStripe;
    }

    public function createBillingAndDeliveryAddresses(Order $order, EntityManagerInterface $entityManager): void
    {
        $payment = $this->stripe->paymentIntents->retrieve($order->getPaymentStripeId(), ['expand' => ['payment_method', 'shipping']]);

        $delivery = new DeliveryAddress();
        $delivery->setAddress($payment->payment_method->billing_details->address['line1']);
        if ($payment->shipping->address['line2'] !== null) {
            $delivery->setAddress($payment->shipping->address['line1'] . ' ' . $payment->shipping->address['line2']);
        }
        $delivery->setPostCode($payment->shipping->address['postal_code'])
            ->setCity($payment->shipping->address['city'])
            ->setCountry($payment->shipping->address['country'])
            ->addOrder($order)
        ;
        $entityManager->persist($delivery);

        $billing = new BillingAddress();
        $billing->setAddress($payment->payment_method->billing_details->address['line1']);
        if ($payment->payment_method->billing_details->address['line2'] !== null) {
            $billing->setAddress($payment->payment_method->billing_details->address['line1'] . ' ' . $payment->payment_method->billing_details->address['line2']);
        }
        $billing->setPostCode($payment->payment_method->billing_details->address['postal_code'])
            ->setCity($payment->payment_method->billing_details->address['city'])
            ->setCountry($payment->payment_method->billing_details->address['country'])
            ->addOrder($order)
        ;
        $entityManager->persist($billing);

        $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "in_delevery"]));

        $today = new \DateTime();
        $estimatedDelivery = $today->add(new \DateInterval('P'.$order->getShipping()->getDeliveryEstimateMaximum().'D'));
        $order->setEstimatedDelivery($estimatedDelivery);

        $entityManager->flush();
    }

    public function checkAfterSucceededPayment(Order $order, EntityManagerInterface $entityManager): void
    {
        $paymentIntent = $this->stripe->paymentIntents->retrieve($order->getPaymentStripeId());

        if ($order->isInStock()) {
            foreach ($order->getOrderItems()->getValues() as $orderItem) {
                $product = $orderItem->getProduct();
                $product->setStock($product->getStock() - $orderItem->getQuantity());
            }
        }
        else {
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $paymentIntent->id,
            ]);

            $order->setRefundStripeId($refund->id);
            $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "cancel"]));
        }

        $entityManager->flush();
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

    public function createShipping(Shipping $shipping): void
    {
        $shippingRates = $this->stripe->shippingRates->create([
            'display_name' => $shipping->getName(),
            'type' => 'fixed_amount',
            'fixed_amount' => [
                'amount' => $shipping->getFee()*100,
                'currency' => 'eur',
            ],
        ]);
        $shipping->setStripeId($shippingRates->id);
    }

    public function updateShipping(Shipping $shipping): void
    {
        $this->stripe->shippingRates->update(
            $shipping->getStripeId(),
            [
                'active' => $shipping->isActive()
            ]
        );
    }

    public function createPromotionCode(PromotionCode $promotionCode): void
    {
        if ($promotionCode->getAmountType() === 'percentage') {
            if ($promotionCode->getExpiresAt() === null) {
                if ($promotionCode->getUseLimit() === null) {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'percent_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'forever',
                    ]);
                }
                else {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'percent_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'forever',
                        'max_redemptions' => $promotionCode->getUseLimit(),
                    ]);
                }

            }
            else {
                if ($promotionCode->getUseLimit() === null) {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'percent_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'once',
                        'redeem_by' => $promotionCode->getExpiresAt()->getTimestamp(),
                    ]);
                }
                else {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'percent_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'once',
                        'redeem_by' => $promotionCode->getExpiresAt()->getTimestamp(),
                        'max_redemptions' => $promotionCode->getUseLimit(),
                    ]);
                }

            }
        }
        elseif ($promotionCode->getAmountType() === 'amount') {
            if ($promotionCode->getExpiresAt() === null) {
                if ($promotionCode->getUseLimit() === null) {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'amount_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'forever',
                    ]);
                }
                else {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'amount_off' => $promotionCode->getAmount(),
                        'currency' => 'EUR',
                        'duration' => 'forever',
                        'max_redemptions' => $promotionCode->getUseLimit(),
                    ]);
                }

            }
            else {
                if ($promotionCode->getUseLimit() === null) {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'amount_off' => $promotionCode->getAmount()*100,
                        'currency' => 'EUR',
                        'duration' => 'once',
                        'redeem_by' => $promotionCode->getExpiresAt()->getTimestamp(),
                    ]);
                }
                else {
                    $coupon = $this->stripe->coupons->create([
                        'name' => $promotionCode->getName(),
                        'amount_off' => $promotionCode->getAmount()*100,
                        'currency' => 'EUR',
                        'duration' => 'once',
                        'redeem_by' => $promotionCode->getExpiresAt()->getTimestamp(),
                        'max_redemptions' => $promotionCode->getUseLimit(),
                    ]);
                }
            }
        }

        $promotionCode->setCouponStripeId($coupon->id);

        if ($promotionCode->getCustomer() === null) {
            if ($promotionCode->isFirstTimeTransaction()) {
                $stripePromotionCode = $this->stripe->promotionCodes->create([
                    'coupon' => $coupon->id,
                    'code' => $promotionCode->getCode(),
                    'restrictions' => [
                        'minimum_amount' => $promotionCode->getMinimumAmount()*100,
                        'minimum_amount_currency' => 'EUR',
                        'first_time_transaction' => true,
                    ],
                ]);
            }
            else {
                $stripePromotionCode = $this->stripe->promotionCodes->create([
                    'coupon' => $coupon->id,
                    'code' => $promotionCode->getCode(),
                    'restrictions' => [
                        'minimum_amount' => $promotionCode->getMinimumAmount()*100,
                        'minimum_amount_currency' => 'EUR',
                    ],
                ]);
            }
        }
        else {
            if ($promotionCode->isFirstTimeTransaction()) {
                $stripePromotionCode = $this->stripe->promotionCodes->create([
                    'coupon' => $coupon->id,
                    'code' => $promotionCode->getCode(),
                    'customer' => $promotionCode->getCustomer()->getStripeId(),
                    'restrictions' => [
                        'minimum_amount' => $promotionCode->getMinimumAmount()*100,
                        'minimum_amount_currency' => 'EUR',
                        'first_time_transaction' => true,
                    ],
                ]);
            }
            else {
                $stripePromotionCode = $this->stripe->promotionCodes->create([
                    'coupon' => $coupon->id,
                    'code' => $promotionCode->getCode(),
                    'customer' => $promotionCode->getCustomer()->getStripeId(),
                    'restrictions' => [
                        'minimum_amount' => $promotionCode->getMinimumAmount()*100,
                        'minimum_amount_currency' => 'EUR',
                    ],
                ]);
            }
        }

        $promotionCode->setStripeId($stripePromotionCode->id);
    }

    public function deletePromotionCode(PromotionCode $promotionCode): void
    {
        $this->stripe->coupons->delete($promotionCode->getCouponStripeId(), []);
    }

}
