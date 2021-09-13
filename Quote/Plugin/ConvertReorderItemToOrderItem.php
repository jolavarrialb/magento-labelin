<?php

declare(strict_types=1);

namespace Labelin\Quote\Plugin;

use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class ConvertReorderItemToOrderItem
{
    public function aroundConvert(ToOrderItem $subject, callable $proceed, $quoteItem, $data = []): OrderItemInterface
    {
        $orderItem = $proceed($quoteItem, $data);

        return $orderItem->setData('is_reordered', $quoteItem->getIsReordered());
    }
}
