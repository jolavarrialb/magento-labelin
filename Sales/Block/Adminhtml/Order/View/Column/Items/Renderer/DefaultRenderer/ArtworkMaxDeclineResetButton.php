<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Adminhtml\Order\View\Column\Items\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Artwork;
use Magento\Backend\Block\Template;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Phrase;

class ArtworkMaxDeclineResetButton extends Template
{
    protected const ARTWORK_MAX_DECLINE_COUNT_RESET_ACTION = 'ArtworkResetDeclineCount';

    public function isAvailable(): bool
    {
        $item = $this->getItem();

        if (null === $item) {
            return false;
        }

        return $item->getArtworkStatus() === Artwork::ARTWORK_STATUS_MAX_CUSTOMER_DECLINE;
    }

    public function getItem(): ?Item
    {
        return $this->getData('item');
    }

    public function getSubmitUrl(): string
    {
        $item = $this->getItem();

        if (!$item) {
            return '#';
        }

        return $this->getUrl(
            sprintf('sales/order_item/%s', static::ARTWORK_MAX_DECLINE_COUNT_RESET_ACTION),
            ['item_id' => $item->getId()]
        );
    }

    public function getButtonLabel(): Phrase
    {
        return __('Max Decline Count by Customer. Reset?');
    }
}
