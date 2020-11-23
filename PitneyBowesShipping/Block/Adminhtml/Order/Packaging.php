<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Block\Adminhtml\Order;

use Labelin\PitneyBowesShipping\Helper\Shipping as ShippingHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Shipping\Block\Adminhtml\Order\Packaging as MagentoPackaging;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\Source\GenericInterface;
use Magento\Shipping\Model\CarrierFactory;

class Packaging extends MagentoPackaging
{
    /** @var ShippingHelper */
    protected $shippingHelper;

    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        GenericInterface $sourceSizeModel,
        Registry $coreRegistry,
        CarrierFactory $carrierFactory,
        ShippingHelper $shippingHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $jsonEncoder,
            $sourceSizeModel,
            $coreRegistry,
            $carrierFactory,
            $data
        );

        $this->shippingHelper = $shippingHelper;
    }

    public function getCarrier(): ?AbstractCarrier
    {
        $order = $this->getShipment()->getOrder();

        if (!$order->getShippingMethod(true)) {
            return null;
        }

        $carrier = $this->_carrierFactory->create($order->getShippingMethod(true)->getCarrierCode());

        return $carrier ?? null;
    }

    public function getShippingHelper(): ShippingHelper
    {
        return $this->shippingHelper;
    }
}
