<?php

namespace App\EventSubscriber;

use App\Entity\Customer;
use App\Repository\OrderRepository;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private OrderSessionStorage $orderSessionStorage;
    private OrderRepository $orderRepository;

    public function __construct(EntityManagerInterface $entityManager, OrderSessionStorage $orderSessionStorage, OrderRepository $orderRepository)
    {
        $this->entityManager = $entityManager;
        $this->orderSessionStorage = $orderSessionStorage;
        $this->orderRepository = $orderRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        $this->orderSessionStorage->removeOrder();
        $user = $event->getToken()->getUser();

        if ($user instanceof Customer) {
            $ordersWithUnusedPromoCodeList = $this->orderRepository->findUncompletedOrderWithPromoCode($user);
            if (!empty($ordersWithUnusedPromoCodeList)) {
                foreach ($ordersWithUnusedPromoCodeList as $order) {
                    $order->getPromotionCode()->removeOrder($order);
                }
                $this->entityManager->flush();
            }
        }
    }


}
