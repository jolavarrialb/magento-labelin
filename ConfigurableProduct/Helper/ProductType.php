<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\Helper;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\AbstractHelper;

class ProductType extends AbstractHelper
{
    protected const TYPE_STICKER        = 'sticker';
    protected const TYPE_PACKAGING_TAPE = 'packaging_tape';

    public function isProductSticker(Product $product): bool
    {
        return $product->getSku() === static::TYPE_STICKER;
    }

    public function isProductPackagingTape(Product $product): bool
    {
        return $product->getSku() === static::TYPE_PACKAGING_TAPE;
    }
}
