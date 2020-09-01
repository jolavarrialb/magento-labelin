<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class Artwork extends AbstractHelper
{
    public const FILE_OPTION_TYPE = 'file';

    public function isArtworkAttachedToOrder(Order $order): bool
    {
        $isArtworkAttached = true;

        foreach ($order->getAllItems() as $orderItem) {
            /** @var Item $orderItem */

            if ($orderItem->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            $isArtworkAttached = false;

            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                break;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $isArtworkAttached = true;
                }
            }
        }

        return $isArtworkAttached;
    }

    public function getOrderArtworksHtml(Order $order): string
    {
        $artworks = '';

        if (!$this->isArtworkAttachedToOrder($order)) {
            return $artworks;
        }

        foreach ($order->getAllItems() as $orderItem) {
            $options = $orderItem->getProductOptionByCode('options');

            if (empty($options)) {
                continue;
            }

            foreach ($options as $option) {
                if ($option['option_type'] === static::FILE_OPTION_TYPE) {
                    $artworks .= $option['value'] . '<br />';
                }
            }
        }

        return $artworks;
    }
}
