<?php

declare(strict_types=1);

namespace Labelin\Catalog\Pricing\Render;

use Magento\Framework\Pricing\Render\Amount as MagentoRenderAmount;

class Amount extends MagentoRenderAmount
{
    protected const PRODUCT_TYPE_SIMPLE = 'simple';

    public function isSimpleOptionsProduct(): bool
    {
        return $this->saleableItem->getTypeId() === static::PRODUCT_TYPE_SIMPLE
            && $this->saleableItem->getHasOptions();
    }
}
