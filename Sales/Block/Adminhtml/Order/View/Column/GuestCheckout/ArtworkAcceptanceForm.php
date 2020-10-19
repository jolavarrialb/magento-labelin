<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\GuestCheckout;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\View\Element\Template;

class ArtworkAcceptanceForm extends Template
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(Template\Context $context, ArtworkHelper $artworkHelper, array $data = [])
    {
        $this->artworkHelper = $artworkHelper;

        parent::__construct($context, $data);
    }

    public function isFormAvailable(): bool
    {
        $orderItem = $this->getOrderItem();

        if (!$orderItem) {
            return false;
        }

        $order = $orderItem->getOrder();

        if (!$order) {
            return false;
        }

        if ($orderItem->getProductType() !== Configurable::TYPE_CODE || !$orderItem->isArtworkDeclineAvailable()) {
            return false;
        }

        return !$orderItem->isArtworkApproved() && $order->getStatus() === Order::STATUS_REVIEW;
    }

    public function isArtworkAttached(): bool
    {
        return $this->artworkHelper->isArtworkAttachedToOrderItem($this->getOrderItem());
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }

    public function getSubmitUrl(): string
    {
        if (!$this->getOrderItem()) {
            return '';
        }

        return $this->getUrl('sales/order_item/guestCheckoutUpdateArtwork', [
            'item_id' => $this->getOrderItem()->getId(),
        ]);
    }
}
