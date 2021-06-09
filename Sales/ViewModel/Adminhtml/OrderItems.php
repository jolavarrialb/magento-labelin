<?php

declare(strict_types=1);

namespace Labelin\Sales\ViewModel\Adminhtml;

use Labelin\Sales\Helper\Product\Premade;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order\Item;

class OrderItems implements ArgumentInterface
{
    /** @var CatalogHelper */
    protected $catalogHelper;

    /** @var Premade */
    protected $premadeHelper;

    public function __construct(
        CatalogHelper $catalogHelper,
        Premade $premadeHelper
    ) {
        $this->catalogHelper = $catalogHelper;
        $this->premadeHelper = $premadeHelper;
    }

    public function getCatalogHelper(): CatalogHelper
    {
        return $this->catalogHelper;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isPremadeProduct(?Item $item): bool
    {
        if (!$item) {
            return false;
        }

        return $this->premadeHelper->isPremade($item);
    }
}
