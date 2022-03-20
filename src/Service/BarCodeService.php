<?php

namespace App\Service;

use App\Entity\RefurbishedToy;

class BarCodeService
{
    public function generateBarCodeNumber(RefurbishedToy $entity): string
    {
        return 'dino-'.$entity->getId().'-'.time();
    }
}
