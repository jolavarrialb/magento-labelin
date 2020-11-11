<?php

declare(strict_types=1);

namespace Labelin\Checkout\Block\Cart\Item\Renderer\Qty;

use Magento\Framework\View\Element\Template;
use Magento\Quote\Model\Quote\Item;

abstract class AbstractRenderer extends Template
{
    public function getItem(): Item
    {
        return $this->getData('item');
    }

    public function getQty(): int
    {
        return (int)$this->getItem()->getQty();
    }
}
