<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\GuestCheckout;

use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Helper\Config\ArtworkDecline as ArtworkDeclineHelper;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class DeclinesQtyInfo extends Template
{
    /** @var ArtworkDeclineHelper */
    protected $artworkDeclineHelper;

    /** @var ArtworkHelper */
    protected $artworkHelper;

    public function __construct(
        Template\Context $context,
        ArtworkDeclineHelper $artworkDeclineHelper,
        ArtworkHelper $artworkHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->artworkDeclineHelper = $artworkDeclineHelper;
        $this->artworkHelper = $artworkHelper;
    }

    public function isAvailable(): bool
    {
        $item = $this->getOrderItem();

        if (!$item) {
            return false;
        }

        return !$item->isArtworkApproved() && $this->artworkHelper->isArtworkAttachedToOrderItem($item);
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }

    public function getAvailableDeclinesQty(): int
    {
        if (!$this->getOrderItem()) {
            return 0;
        }

        return $this->artworkDeclineHelper->getDeclinesQty() - $this->getOrderItem()->getArtworkDeclinesCount();
    }
}
