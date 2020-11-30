<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Labelin\PitneyBowesShipping\Model\Carrier\FixedPrice;
use Labelin\PitneyBowesShipping\Model\Carrier\FreeShipping;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Shipment;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\CarrierFactory;

class Shipping extends AbstractHelper
{
    /** @var CarrierFactory */
    protected $carrierFactory;

    public function __construct(Context $context, CarrierFactory $carrierFactory)
    {
        parent::__construct($context);

        $this->carrierFactory = $carrierFactory;
    }

    public function isPitneyBowesShippingByCarrier(?AbstractCarrier $carrier): bool
    {
        if (!$carrier) {
            return false;
        }

        return in_array(
            $carrier->getCarrierCode(),
            [
                FreeShipping::SHIPPING_CODE,
                FixedPrice::SHIPPING_CODE,
            ],
            false
        );
    }

    public function isPitneyBowesShippingByShipment(?Shipment $shipment): bool
    {
        if (!$shipment) {
            return false;
        }

        $carrier = $this->getCarrierByShipment($shipment);

        if (!$carrier) {
            return false;
        }

        return in_array(
            $carrier->getCarrierCode(),
            [
                FreeShipping::SHIPPING_CODE,
                FixedPrice::SHIPPING_CODE,
            ],
            false
        );
    }

    public function getCarrierByShipment(?Shipment $shipment): ?AbstractCarrier
    {
        if (!$shipment) {
            return null;
        }

        $order = $shipment->getOrder();

        if (!$order->getShippingMethod(true)) {
            return null;
        }

        return $this->carrierFactory->create($order->getShippingMethod(true)->getCarrierCode());
    }
}
