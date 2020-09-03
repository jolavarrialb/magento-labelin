<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\Backend\Block\Template;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

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

        if (!$orderItem ||
            !$orderItem->getOrder() ||
            $orderItem->getProductType() !== Configurable::TYPE_CODE ||
            !$orderItem->isArtworkDeclineAvailable()
        ) {
            return false;
        }

        return $this->artworkHelper->isArtworkAttachedToOrderItem($this->getOrderItem()) &&
            !$orderItem->isArtworkApproved() &&
            $orderItem->getOrder()->getStatus() === Order::STATUS_REVIEW;
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

        return $this->getUrl('sales/order_item/updateArtwork', ['item_id' => $this->getOrderItem()->getId()]);
    }
}
