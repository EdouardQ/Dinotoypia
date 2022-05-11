<?php

namespace App\Service;

use App\Entity\DeliveryAddress;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryCheckerService
{
    public function check(array $data): bool
    {
        if ($data['type']->getName() === "Livraison Mondial Relais") {
            if (
                (
                    $data['relais_id'] !== null && $data['relais_address'] !== null &&
                    $data['relais_city'] !== null && $data['relais_post_code'] !== null
                )
                &&
                (
                    $data['address'] === null && $data['city'] === null && $data['post_code'] === null
                )
            ) {
                return true;
            }
            return false;
        }
        elseif ($data['type']->getName() === "Livraison Colissimo" || $data['type']->getName() === "Livraison Chronopost") {
            if (
                (
                    $data['relais_id'] === null && $data['relais_address'] === null &&
                    $data['relais_city'] === null && $data['relais_post_code'] === null
                )
                &&
                (
                    $data['address'] !== null && $data['city'] !== null && $data['post_code'] !== null
                )
            ) {
                return true;
            }
            return false;
        }
    }

    public function updateOrderDeliveryInfos(array $data, Order $order, EntityManagerInterface $entityManager): void
    {
        if ($order->getDeliveryAddress() === null) {
            $delivery = new DeliveryAddress();
        }
        else {
            $delivery = $order->getDeliveryAddress();
        }

        $delivery->setCountry("FR");

        if ($data['type']->getName() === "Livraison Mondial Relais") {
            $delivery->setAddress($data['relais_address'])
                ->setCity($data['relais_city'])
                ->setPostCode($data['relais_post_code'])
                ->setRelayPointId($data['relais_id'])
            ;
        }
        elseif ($data['type']->getName() === "Livraison Colissimo" || $data['type']->getName() === "Livraison Chronopost") {
            $delivery->setAddress($data['address'])
                ->setCity($data['city'])
                ->setPostCode($data['post_code'])
                ->setRelayPointId(null)
            ;
        }
        $delivery->addOrder($order);

        if ($order->getDeliveryAddress() === null) {
            $entityManager->persist($delivery);
        }

        $order->setShipping($data['type']);

        $entityManager->flush();
    }
}
