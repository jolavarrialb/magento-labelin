<?php

declare(strict_types=1);

namespace Labelin\Sales\ViewModel\Adminhtml;

use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OrderItems implements ArgumentInterface
{
    /** @var CatalogHelper */
    protected $catalogHelper;

    public function __construct(CatalogHelper $catalogHelper)
    {
        $this->catalogHelper = $catalogHelper;
    }

    public function getCatalogHelper(): CatalogHelper
    {
        return $this->catalogHelper;
    }
}
