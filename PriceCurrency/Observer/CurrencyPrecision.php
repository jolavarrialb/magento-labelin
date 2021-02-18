<?php

declare(strict_types=1);

namespace Labelin\PriceCurrency\Observer;

use Labelin\PriceCurrency\Model\Directory\Currency;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CurrencyPrecision implements ObserverInterface
{
    public function execute(Observer $observer): self
    {
        $currencyOptions = $observer->getEvent()->getCurrencyOptions();
        $currencyOptions->setData('precision', Currency::DEFAULT_PRECISION);

        return $this;
    }
}
