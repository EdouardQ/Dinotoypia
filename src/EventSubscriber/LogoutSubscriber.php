<?php

namespace App\EventSubscriber;

use App\Storage\OrderSessionStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    private OrderSessionStorage $orderSessionStorage;

    public function __construct(OrderSessionStorage $orderSessionStorage)
    {
        $this->orderSessionStorage = $orderSessionStorage;
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
    }


}
