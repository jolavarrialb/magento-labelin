<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Product;

use Labelin\Sales\Helper\Config\ArtworkSizes;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Item;
use Magento\Store\Model\StoreManagerInterface;

class Premade extends AbstractHelper
{
    public const PREMADE_DESIGNER = 'PRE-MADE';

    protected const PRODUCT_IMAGE_GRID_SIZE = 'product_comparison_list';

    protected const CATALOG_PRODUCT_MEDIA_PATCH = 'catalog/product';

    /** @var Image */
    protected $imageHelper;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var ArtworkSizes */
    protected $artworkSizesHelper;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Image $imageHelper,
        ArtworkSizes $artworkSizesHelper
    ) {
        parent::__construct($context);

        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->artworkSizesHelper = $artworkSizesHelper;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isPremade(Item $item): bool
    {
        return null === $item->getParentItemId() && Type::DEFAULT_TYPE === $item->getProductType();
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
        );
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductAbsolutePath(Product $product): string
    {
        $image = $product->getMediaGalleryImages()->getFirstItem();

        return $image->getPath() ?? '';
    }
}
