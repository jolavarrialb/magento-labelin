<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class MaxDeclineQtyInfo extends Template
{
    public function isAvailable(): bool
    {
        $item = $this->getOrderItem();

        if (null === $item) {
            return false;
        }

        return $item->getArtworkStatus() === Artwork::ARTWORK_STATUS_MAX_CUSTOMER_DECLINE;
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }
}
