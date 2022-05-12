<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderSessionStorage
{
    private RequestStack $requestStack;

    const CART_KEY_NAME = 'cart';
    const CHECKOUT_STRIPE_ID = 'checkout_stripe_id';
    const ORDER_ID_KEY_NAME = 'order_id';
    const ORDER_KEY_NAME = 'order';

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getOrder(): ?array
    {
        return $this->getSession()->get(self::ORDER_KEY_NAME);
    }

    public function setOrder(array $orderItems): void
    {
        $this->requestStack->getSession()->set(self::ORDER_KEY_NAME, $orderItems);
    }

    public function removeOrder(): void
    {
        $this->getSession()->remove(self::CART_KEY_NAME);
        $this->getSession()->remove(self::CHECKOUT_STRIPE_ID);
        $this->getSession()->remove(self::ORDER_ID_KEY_NAME);
        $this->getSession()->remove(self::ORDER_KEY_NAME);

    }

    public function getCart(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    public function setCart(int $nb): void
    {
        $this->requestStack->getSession()->set(self::CART_KEY_NAME, $nb);
    }

    public function getOrderId(): ?int
    {
        return $this->getSession()->get(self::ORDER_ID_KEY_NAME);
    }

    public function setOrderId(int $id): void
    {
        $this->requestStack->getSession()->set(self::ORDER_ID_KEY_NAME, $id);
    }

    public function getCheckoutStripeId(): ?int
    {
        return $this->getSession()->get(self::CHECKOUT_STRIPE_ID);
    }

    public function setCheckoutStripeId(int $id): void
    {
        $this->requestStack->getSession()->set(self::CHECKOUT_STRIPE_ID, $id);
    }
}
