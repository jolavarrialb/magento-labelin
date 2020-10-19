<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\View\Element\Template;

class NotInReviewInfo extends Template
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
        if (empty($this->getOrderItem())) {
            return true;
        }

        return !$this->artworkHelper->isArtworkInReview($this->getOrderItem());
    }

    public function getOrderItem(): ?Item
    {
        return $this->getData('item');
    }
}
