<?php

namespace Labelin\ProductionTicket\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class AddArtworkToProductionToReorderItem
{
    public function aroundAddOrderItem(Cart $subject, callable $proceed, $orderItem, $qtyFlag = null)
    {
        $result = $proceed($orderItem, $qtyFlag);

        if (!$artwork = $orderItem->getArtworkToProduction()) {
            return $result;
        }

        foreach ($result->getItems()->getItems() as $item) {
            if ($item->getProductType() === Configurable::TYPE_CODE &&
                !$item->getArtworkToProduction() &&
                $item->getIsReordered()) {

                $item->setData('artwork_to_production', $artwork);
            }
        }

        return $result;
    }
}
