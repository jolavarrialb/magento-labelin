<?php

declare(strict_types=1);

namespace Labelin\Checkout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Msrp\Helper\Data as MsrpHelper;
use Magento\Tax\Helper\Data as TaxHelper;

class CheckoutCartForm implements ArgumentInterface
{
    /** @var TaxHelper */
    protected $taxHelper;

    /** @var MsrpHelper */
    protected $msrpHelper;

    public function __construct(TaxHelper $taxHelper, MsrpHelper $msrpHelper)
    {
        $this->taxHelper = $taxHelper;
        $this->msrpHelper = $msrpHelper;
    }

    public function getTaxHelper(): TaxHelper
    {
        return $this->taxHelper;
    }

    public function getMsrpHelper(): MsrpHelper
    {
        return $this->msrpHelper;
    }
}
