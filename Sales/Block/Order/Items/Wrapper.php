<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class Wrapper extends Template
{
    /** @var Artwork */
    protected $artworkHelper;

    public function __construct(
        Template\Context $context,
        Artwork $artworkHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->artworkHelper = $artworkHelper;
    }

    public function isAvailable(): bool
    {
        if (!$this->getOrderItem()) {
            return false;
        }

        /** @var Order */
        $order = $this->getOrderItem()->getOrder();

        if (!$order) {
            return false;
        }

        return ($order->getState() === Order::STATE_NEW || $order->getState() === Order::STATE_HOLDED) &&
            ($order->getStatus() === Order::STATUS_REVIEW || $order->getStatus() === Order::STATE_HOLDED);
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }
}
