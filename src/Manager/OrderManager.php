<?php

namespace App\Manager;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\State;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    private EntityManagerInterface $entityManager;
    private OrderSessionStorage $orderSessionStorage;

    public function __construct(EntityManagerInterface $entityManager, OrderSessionStorage $orderSessionStorage,)
    {
        $this->entityManager = $entityManager;
        $this->orderSessionStorage = $orderSessionStorage;
    }

    public function getOrderSession(): array
    {
        $order = $this->orderSessionStorage->getOrder();

        if (!$order) {
            $order = [];
            $this->orderSessionStorage->setOrder($order);
        }

        return $order;
    }

    public function getOrder(Customer $customer = null): Order
    {
        $order = $this->retrieveOrder();

        if (!$order) {
            $this->createOrder($customer);
            $order = $this->retrieveOrder();
        }

        return $order;
    }

    public function addItemToOrderSession(int $id): void
    {
        $order = $this->getOrderSession();

        if (array_key_exists($id, $order)) {
            $order[$id] += 1;
        }
        else {
            $order[$id] = 1;
        }

        $this->orderSessionStorage->setOrder($order);
    }

    public function removeItemToOrderSession(int $id): void
    {
        $order = $this->getOrderSession();

        if ($order[$id] >= 2) {
            $order[$id] -= 1;
        }
        else {
            unset($order[$id]);
        }

        $this->orderSessionStorage->setOrder($order);
    }

    public function purgeOrderSession(): void
    {
        $this->orderSessionStorage->removeOrder();
    }

    public function createOrder(Customer $customer): void
    {
        $orderArray = $this->getOrderSession();
        $productRepository = $this->entityManager->getRepository(Product::class);

        $order = new Order();
        $order->setCustomer($customer);
        $this->entityManager->persist($order);

        foreach ($orderArray as $id => $quantity) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order)
                ->setProduct($productRepository->find($id))
                ->setQuantity($quantity)
            ;

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->flush();
        $this->orderSessionStorage->setOrderId($order->getId());
    }

    public function retrieveOrder(): ?Order
    {
        $id = $this->orderSessionStorage->getOrderId();
        if ($id) {
            return $this->entityManager->getRepository(Order::class)->find($id);
        }
        return null;
    }

    public function updateCart(): void
    {
        $order = $this->getOrderSession();
        $n = 0;

        foreach ($order as $id => $quantity) {
            $n += $quantity;
        }
        $this->orderSessionStorage->setCart($n);
    }

    public function createCheckout(): array
    {
        $orderSession = $this->getOrderSession();
        $productRepository = $this->entityManager->getRepository(Product::class);
        $orderItems = [];
        $total = 0;

        foreach ($orderSession as $id => $quantity) {
            $entity = new OrderItem();
            $product = $productRepository->find($id);
            $entity->setProduct($product)
                ->setQuantity($quantity)
                ->setPrice($product->getPrice())
            ;
            $orderItems[] = $entity;
            $total += $entity->getPrice() * $entity->getQuantity();
        }

        return [
            'orderItems' => $orderItems,
            'total' => $total,
        ];
    }

    public function checkUpdateAndFixOrder(Order $order): bool
    {
        $this->entityManager->refresh($order);
        $orderItemArray = $order->getOrderItems()->getValues();
        $orderSession = $this->getOrderSession();
        $orderSessionCalc = $orderSession;
        $isOutOfStock = false;

        foreach ($orderItemArray as $orderItem) {
            // if an orderItem has been removed
            if (!array_key_exists($orderItem->getProduct()->getId(), $orderSession)) {
                $this->entityManager->remove($orderItem);
                unset($orderSessionCalc[$orderItem->getProduct()->getId()]);
            }
            // remove out of stock orderItem
            elseif ($orderItem->getProduct()->getStock() < $orderItem->getQuantity()) {
                unset($orderSession[$orderItem->getProduct()->getId()]);
                $this->entityManager->remove($orderItem);
                unset($orderSessionCalc[$orderItem->getProduct()->getId()]);
                $isOutOfStock = true;
            }
            // if the quantity has changed
            elseif ($orderSession[$orderItem->getProduct()->getId()] != $orderItem->getQuantity()) {
                $orderSession[$orderItem->getProduct()->getId()] = $orderItem->getQuantity();
                unset($orderSessionCalc[$orderItem->getProduct()->getId()]);
            }
            // if the orderItem didn't change
            else {
                unset($orderSessionCalc[$orderItem->getProduct()->getId()]);
            }
        }

        // if orderItem has been added
        if (!empty($orderSessionCalc)) {
            foreach ($orderSessionCalc as $id => $quantity) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order)
                    ->setProduct($this->entityManager->getRepository(Product::class)->find($id))
                    ->setQuantity($quantity)
                ;
                $this->entityManager->persist($orderItem);
            }
        }

        $this->orderSessionStorage->setOrder($orderSession);
        $this->entityManager->flush();

        $this->updateCart();
        return $isOutOfStock;
    }
}
