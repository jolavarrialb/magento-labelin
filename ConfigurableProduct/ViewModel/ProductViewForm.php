<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\ViewModel;

use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductViewForm implements ArgumentInterface
{
    /** @var OutputHelper */
    protected $outputHelper;

    public function __construct(OutputHelper $outputHelper)
    {
        $this->outputHelper = $outputHelper;
    }

    public function getOutputHelper(): OutputHelper
    {
        return $this->outputHelper;
    }
}
