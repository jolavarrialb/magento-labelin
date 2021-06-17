<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Config\ArtworkSizes;
use Labelin\Sales\Helper\Product\Premade;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class PremadeProductImage extends Template
{


    /** @var Premade */
    protected $premadeHelper;

    public function __construct(
        Template\Context $context,
        Premade $premadeHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->premadeHelper = $premadeHelper;
    }

    /**
     * @param Product|null $product
     * @return int
     */
    public function productHasImages(?Product $product): int
    {
        if (!$product) {
            return 0;
        }

        return $product->getMediaGalleryImages()->getSize();
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductImageUrl(Product $product): string
    {
        return $this->premadeHelper->getProductImageUrl($product);
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductOriginalImageUrl(Product $product): string
    {
        return $this->premadeHelper->getProductOriginalImageUrl($product);
    }
}
