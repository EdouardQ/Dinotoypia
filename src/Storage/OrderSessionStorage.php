<?php

namespace App\Storage;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderSessionStorage
{
    private OrderRepository $orderRepository;
    private RequestStack $requestStack;

    const ORDER_KEY_NAME = 'order_id';

    public function __construct(OrderRepository $orderRepository, RequestStack $requestStack)
    {
        $this->orderRepository = $orderRepository;
        $this->requestStack = $requestStack;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function setOrder(Order $order): void
    {
        $this->requestStack->getSession()->set(self::ORDER_KEY_NAME, $order->getId());
    }

    public function getOrder(): ?Order
    {
        return $this->orderRepository->findOneBy(['id' => $this->getOrderId()]);
    }

    private function getOrderId(): ?int
    {
        return $this->getSession()->get(self::ORDER_KEY_NAME);
    }

    public function removeOrder(): void
    {
        $this->requestStack->getSession()->remove(self::ORDER_KEY_NAME);
    }
}
