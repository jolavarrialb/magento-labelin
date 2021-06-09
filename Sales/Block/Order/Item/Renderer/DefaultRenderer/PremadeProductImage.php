<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Item\Renderer\DefaultRenderer;

use Labelin\Sales\Helper\Config\ArtworkSizes;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class PremadeProductImage extends Template
{
    protected const PRODUCT_IMAGE_GRID_SIZE = 'product_comparison_list';

    protected const CATALOG_PRODUCT_MEDIA_PATCH = 'catalog/product';

    /** @var Image */
    protected $imageHelper;

    /** @var StoreManagerInterface  */
    protected $storeManager;

    protected $artworkSizesHelper;

    public function __construct(
        Template\Context $context,
        Image $imageHelper,
        StoreManagerInterface $storeManager,
        ArtworkSizes $artworkSizesHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->artworkSizesHelper = $artworkSizesHelper;
    }

    /**
     * @param Product|null $product
     * @return int
     */
    public function productHasImages(?Product $product): int
    {
        if (null === $product) {
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
        return $this->imageHelper
            ->init($product, static::PRODUCT_IMAGE_GRID_SIZE,)
            ->setImageFile($product->getSmallImage())
            ->resize($this->artworkSizesHelper->getConfigHeight(), $this->artworkSizesHelper->getConfigWidth())
            ->getUrl();
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductOriginalImageUrl(Product $product): string
    {
        return sprintf(
            '%s%s%s',
            $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA),
            static::CATALOG_PRODUCT_MEDIA_PATCH,
            $product->getImage()
        ) ;
    }
}
