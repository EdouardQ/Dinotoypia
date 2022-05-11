<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderSessionStorage
{
    private RequestStack $requestStack;

    const ORDER_KEY_NAME = 'order';
    const ORDER_ID_KEY_NAME = 'order_id';
    const CART_KEY_NAME = 'cart';

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
        $this->requestStack->getSession()->remove(self::ORDER_KEY_NAME);
        $this->requestStack->getSession()->remove(self::ORDER_ID_KEY_NAME);
        $this->requestStack->getSession()->remove(self::CART_KEY_NAME);
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
}
