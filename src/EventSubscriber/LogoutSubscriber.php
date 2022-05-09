<?php

namespace App\EventSubscriber;

use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        if ($event->getToken()->getUser() instanceof Customer) {
            $cookie = Cookie::create('order')
                ->withValue(0)
                ->withExpires(time() + 172800)
                ->withSecure(false)
                ->withHttpOnly(false)
            ;

            $event->getResponse()->headers->setCookie($cookie);
        }
    }
}
