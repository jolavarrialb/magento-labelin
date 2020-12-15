<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\ViewModel;

use Labelin\ConfigurableProduct\Helper\ProductType as ProductTypeHelper;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductViewForm implements ArgumentInterface
{
    /** @var OutputHelper */
    protected $outputHelper;

    /** @var ProductTypeHelper */
    protected $productTypeHelper;

    public function __construct(OutputHelper $outputHelper, ProductTypeHelper $productTypeHelper)
    {
        $this->outputHelper = $outputHelper;
        $this->productTypeHelper = $productTypeHelper;
    }

    public function getOutputHelper(): OutputHelper
    {
        return $this->outputHelper;
    }

    public function getProductTypeHelper(): ProductTypeHelper
    {
        return $this->productTypeHelper;
    }
}
