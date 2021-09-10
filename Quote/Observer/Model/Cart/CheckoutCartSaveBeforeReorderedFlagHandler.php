<?php

declare(strict_types=1);

namespace Labelin\Quote\Observer\Model\Cart;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckoutCartSaveBeforeReorderedFlagHandler implements ObserverInterface
{
    public function execute(Observer $observer): self
    {
        $cart = $observer->getEvent()->getData('cart');

        foreach ($cart->getItems() as $item) {
            if (null !== $item->getIsReordered()) {
                continue;
            }

            $item->setIsReordered(0);
        }

        return $this;
    }
}
