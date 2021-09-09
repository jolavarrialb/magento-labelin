<?php

namespace Labelin\ProductionTicket\Plugin;

use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class ConvertQuoteArtworkItemToOrderItem
{
    public function aroundConvert(ToOrderItem $subject, callable $proceed, $quoteItem, $data = []): OrderItemInterface
    {
        $orderItem = $proceed($quoteItem, $data);

        return $orderItem->setData('artwork_to_production', $quoteItem->getArtworkToProduction());
    }
}
