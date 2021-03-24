<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Product;

use Magento\Catalog\Model\Product\Type;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order\Item;

class Premade extends AbstractHelper
{
    public const PREMADE_DESIGNER = 'PRE-MADE';

    public function isPremade(Item $item): bool
    {
        return null !== $item->getParentItemId() && Type::DEFAULT_TYPE === $item->getProductType();
    }
}
