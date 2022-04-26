<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\State;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;

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

    public function createOrderItem(Product $product): void
    {
        $order = $this->getCurrentOrder();
        $this->save($order);

        $productAlreadyExistsInOrder = false;

        foreach ($order->getOrderItems()->getValues() as $orderItem) {
            if ($orderItem->getProduct() == $product) {
                $orderItem->setQuantity($orderItem->getQuantity()+1);
                $productAlreadyExistsInOrder = true;
            }
        }

        if (!$productAlreadyExistsInOrder) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setPrice($product->getPrice());
            $orderItem->setQuantity(1);
            $orderItem->setOrder($order);

            $order->addOrderItem($orderItem);

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->flush();
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
