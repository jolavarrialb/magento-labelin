<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Helper\Config\ArtworkDecline as ArtworkDeclineHelper;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class DeclinesQtyInfo extends Template
{
    /** @var ArtworkDeclineHelper */
    protected $artworkDeclineHelper;

    public function __construct(
        Template\Context $context,
        ArtworkDeclineHelper $artworkDeclineHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->artworkDeclineHelper = $artworkDeclineHelper;
    }

    public function isAvailable(): bool
    {
        return !$this->artworkDeclineHelper->hasArtworkUnlimitedDeclines() && $this->getAvailableDeclinesQty() > 0;
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
