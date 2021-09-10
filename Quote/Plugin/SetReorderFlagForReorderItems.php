<?php

declare(strict_types=1);

namespace Labelin\Quote\Plugin;

use Magento\Checkout\Model\Cart;

class SetReorderFlagForReorderItems
{
    public function aroundAddOrderItem(Cart $subject, callable $proceed, $orderItem, $qtyFlag = null)
    {
        $result = $proceed($orderItem, $qtyFlag);

        foreach ($result->getItems()->getItems() as $item) {
            if (null !== $item->getIsReordered()) {
                continue;
            }

            $item->setData('is_reordered', $orderItem->getIsReordered());
        }

        return $result;
    }
}
