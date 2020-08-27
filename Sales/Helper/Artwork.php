<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class Artwork extends AbstractHelper
{
    protected const FILE_OPTION_TYPE = 'file';

    public function isArtworkAttachedToOrder(Order $order): bool
    {
        $isOnHoldStatusAvailable = true;

        foreach ($order->getAllItems() as $orderItem) {
            /** @var Item $orderItem */

            if ($orderItem->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                break;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $isOnHoldStatusAvailable = false;
                }
            }
        }

        return $isOnHoldStatusAvailable;
    }
}
