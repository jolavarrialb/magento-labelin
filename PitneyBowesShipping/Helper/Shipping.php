<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Labelin\PitneyBowesShipping\Model\Carrier\FixedPrice;
use Labelin\PitneyBowesShipping\Model\Carrier\FreeShipping;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Shipping\Model\Carrier\AbstractCarrier;

class Shipping extends AbstractHelper
{
    public function isPitneyBowesShipping(?AbstractCarrier $carrier): bool
    {
        if (!$carrier) {
            return false;
        }

        return in_array(
            $carrier->getCarrierCode(),
            [
                FreeShipping::SHIPPING_CODE,
                FixedPrice::SHIPPING_CODE,
            ],
            false
        );
    }
}
