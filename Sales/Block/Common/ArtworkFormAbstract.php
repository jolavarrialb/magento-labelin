<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Common;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Helper\Instructions as InstructionsHelper;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

abstract class ArtworkFormAbstract extends Template
{
    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var InstructionsHelper */
    protected $instructionsHelper;

    public function __construct(
        Template\Context $context,
        ArtworkHelper $artworkHelper,
        InstructionsHelper $instructionsHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->artworkHelper = $artworkHelper;
        $this->instructionsHelper = $instructionsHelper;
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

        return !$orderItem->isArtworkApproved() &&
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
