<?php

declare(strict_types=1);

namespace Labelin\Checkout\Plugin;

use Labelin\Checkout\Helper\ArtworkPreview;
use Magento\Checkout\CustomerData\DefaultItem;
use Magento\Checkout\Model\Session;

class MinicartStickerImage
{
    /** @var ArtworkPreview */
    protected $artworkPreview;

    /** @var Session */
    protected $checkoutSession;

    public function __construct(ArtworkPreview $artworkPreview, Session $checkoutSession)
    {
        $this->artworkPreview = $artworkPreview;
        $this->checkoutSession = $checkoutSession;
    }

    public function afterGetItemData(DefaultItem $subject, $result): array
    {
        $item = $this->checkoutSession->getQuote()->getItemById($result['item_id']);
        $url = $this->artworkPreview->getArtworkUrlByItemAndOptions($item, $result['options']);

        if ($url) {
            $result['product_image']['src'] = $url;
        }

        return $result;
    }
}
