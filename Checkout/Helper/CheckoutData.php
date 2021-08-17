<?php

declare(strict_types=1);

namespace Labelin\Checkout\Helper;

use Magento\Checkout\Helper\Data;

class CheckoutData extends Data
{
    protected const PRICE_TRIPLE_PRECISION = 3;

    /**
     * @param float $price
     * @return float|string
     */
    public function getTriplePrecisionPrice(float $price)
    {
        return $this->priceCurrency->format(
            $price,
            true,
            static::PRICE_TRIPLE_PRECISION,
            $this->getQuote()->getStore()
        );
    }
}
