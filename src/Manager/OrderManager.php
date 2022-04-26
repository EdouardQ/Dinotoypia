<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\State;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class OrderManager
 * @package App\Manager
 */
class OrderManager
{
    private EntityManagerInterface $entityManager;
    private OrderSessionStorage $orderSessionStorage;

    public function __construct(EntityManagerInterface $entityManager, OrderSessionStorage $orderSessionStorage,)
    {
        $this->entityManager = $entityManager;
        $this->orderSessionStorage = $orderSessionStorage;
    }

    public function getCurrentOrder(): Order
    {
        $order = $this->orderSessionStorage->getOrder();

        if (!$order) {
            $order = new Order();
            $order->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setState($this->entityManager->getRepository(State::class)->findOneBy(['code' => 'pending']))
            ;
        }

        return $order;
    }

    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->orderSessionStorage->setOrder($order);
    }

    public function createQuantityCookie(): Cookie
    {
        return Cookie::create('order')
            ->withValue($this->getCurrentOrder()->getTotalQuantity())
            ->withExpires(time() + 172800)
            ->withSecure(false)
            ->withHttpOnly(false)
            ;
    }
}
