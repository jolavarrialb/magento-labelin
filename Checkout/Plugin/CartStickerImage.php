<?php

declare(strict_types=1);

namespace Labelin\Checkout\Plugin;

use Labelin\Checkout\Helper\ArtworkPreview;
use Magento\Catalog\Block\Product\Image;
use Magento\Checkout\Block\Cart\Item\Renderer;

class CartStickerImage
{
    /** @var ArtworkPreview */
    protected $artworkPreview;

    public function __construct(ArtworkPreview $artworkPreview)
    {
        $this->artworkPreview = $artworkPreview;
    }

    public function afterGetImage(Renderer $subject, $result): Image
    {
        $url = $this->artworkPreview->getArtworkUrlByItemAndOptions($subject->getItem(), $subject->getOptionList());

        if ($url) {
            $result->setData('image_url', $url);
        }

        return $result;
    }
}
