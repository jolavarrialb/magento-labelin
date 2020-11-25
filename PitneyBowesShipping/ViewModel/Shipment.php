<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\ViewModel;

use Labelin\PitneyBowesShipping\Helper\Shipping as ShippingHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Shipment implements ArgumentInterface
{
    /** @var ShippingHelper */
    protected $shippingHelper;

    public function __construct(ShippingHelper $shippingHelper)
    {
        $this->shippingHelper = $shippingHelper;
    }

    public function getShippingHelper(): ShippingHelper
    {
        return $this->shippingHelper;
    }
}
