<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\PromotionCode;
use App\Repository\StateRepository;

class PromotionCodeService
{
    public function checkUseCondition(Customer $customer, Order $order, PromotionCode $promotionCode): bool
    {
        // verify if the code comes from a refurbishedToy and if the customer is the good one
        if ($promotionCode->getRefurbishedToy() && $promotionCode->getRefurbishedToy()->getCustomer() !== $customer) {
            return false;
        }

        // verify if the code has a limit of use and if it is over-used
        if ($promotionCode->getUseLimit() && $promotionCode->isOverUsed()) {
            return false;
        }

        // verify if the code has an expiration and if it is expired
        if ($promotionCode->getExpiresAt() && $promotionCode->getExpiresAt() < new \DateTime()) {
            return false;
        }

        // verify if the code is for the new customer and if teh customer is it
        if ($promotionCode->isFirstTimeTransaction() && $this->customerHasAlreadyBuySomething($customer)) {
            return false;
        }

        // verify if the code is over-used by the customer
        $promotionCodesAlreadyUsed = [];
        foreach ($customer->getOrders()->getValues() as $order) {
            if ($order->getPromotionCode()) {
                if (!array_key_exists($order->getPromotionCode()->getId(), $promotionCodesAlreadyUsed)) {
                    $promotionCodesAlreadyUsed[$order->getPromotionCode()->getId()] = 0;
                }
                $promotionCodesAlreadyUsed[$order->getPromotionCode()->getId()] += 1;
            }
        }

        foreach ($promotionCodesAlreadyUsed as $id => $numberUse) {
            if ($id === $promotionCode->getId() && $numberUse >= $promotionCode->getUseLimitPerCustomer()) {
                return false;
            }
        }

        // verify the minimum amount
        if ($order->getTotalPriceOfOrderItems() < floatval($promotionCode->getMinimumAmount())) {
            return false;
        }

        return true;
    }

    private function customerHasAlreadyBuySomething(Customer $customer): bool
    {
        $orders = $customer->getOrders()->getValues();
        foreach ($orders as $order) {
            if ($order->getState()->getCode() !== 'pending') {
                return true;
            }
        }
        return false;
    }
}
