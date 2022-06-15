<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\PromotionCode;

class PromotionCodeService
{
    public function checkUseCondition(Customer $customer, PromotionCode $promotionCode): bool
    {
        $promotionCodesAlreadyUsed = [];
        foreach ($customer->getOrders()->getValues() as $order) {
            if ($order->getPromotionCode()) {
                if (!array_key_exists($order->getPromotionCode()->getId(), $promotionCodesAlreadyUsed)) {
                    $promotionCodesAlreadyUsed[$order->getPromotionCode()->getId()] = 0;
                }
                $promotionCodesAlreadyUsed[$order->getPromotionCode()->getId()] += 1;
            }
        }

        if (empty($promotionCodesAlreadyUsed)) {
            return true;
        }

        foreach ($promotionCodesAlreadyUsed as $id => $use) {
            if ($id === $promotionCode->getId() && $use >= $promotionCode->getUseLimitPerCustomer()) {
                return false;
            }
        }

        return true;
    }
}
