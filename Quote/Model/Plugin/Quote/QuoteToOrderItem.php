<?php

namespace Labelin\Quote\Model\Plugin\Quote;


use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Order\Item;

class QuoteToOrderItem
{
    public function aroundConvert(ToOrderItem $subject, callable $proceed, AbstractItem $quoteItem, $additional = []): Item
    {
        $orderItem = $proceed($quoteItem, $additional);

        return $orderItem->setIsReordered((bool)$quoteItem->getIsReordered());
    }

}
