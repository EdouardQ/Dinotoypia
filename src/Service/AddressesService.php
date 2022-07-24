<?php

namespace App\Service;

use App\Entity\BillingAddress;
use App\Entity\DeliveryAddress;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressesService
{
    public function addDeliveryAddressToOrder(Order $order, array $data, EntityManagerInterface $entityManager): void
    {
        $delivery = $entityManager->getRepository(DeliveryAddress::class)->findOneBy($data);

        if ($delivery) {
            $delivery->addOrder($order);
        }
        else {
            $delivery = new DeliveryAddress();
            $delivery->setAddress($data['address'])
                ->setCity($data['city'])
                ->setPostCode($data['postCode'])
                ->setCountry($data['country'])
                ->addOrder($order)
            ;
            $entityManager->persist($delivery);
            $entityManager->flush();
        }
    }

    public function addBillingAddressToOrder(Order $order, array $data, EntityManagerInterface $entityManager): void
    {
        $billing = $entityManager->getRepository(BillingAddress::class)->findOneBy($data);

        if ($billing) {
            $billing->addOrder($order);
        }
        else {
            $billing = new BillingAddress();
            $billing->setAddress($data['address'])
                ->setCity($data['city'])
                ->setPostCode($data['postCode'])
                ->setCountry($data['country'])
                ->addOrder($order)
            ;
            $entityManager->persist($billing);
        }
    }

    public function addDeliveryAddressToOrderByRequest(Order $order, Request $request, EntityManagerInterface $entityManager): void
    {
        $data = [
            'address' => $request->request->get('address'),
            'city' => $request->request->get('city'),
            'postCode' => $request->request->get('post_code'),
            'country' => $request->request->get('country'),
        ];

        $this->addDeliveryAddressToOrder($order, $data, $entityManager);
    }
}