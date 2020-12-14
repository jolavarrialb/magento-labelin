<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Block\Adminhtml;

use Labelin\PitneyBowesShipping\Helper\Shipping as ShippingHelper;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Shipping\Block\Adminhtml\View as MagentoView;

class View extends MagentoView
{
    /** @var ShippingHelper */
    protected $shippingHelper;

    public function __construct(Context $context, Registry $registry, ShippingHelper $shippingHelper, array $data = [])
    {
        $this->shippingHelper = $shippingHelper;

        parent::__construct($context, $registry, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        if ($this->shippingHelper->isPitneyBowesShippingByShipment($this->getShipment())) {
            $this->buttonList->add(
                'cancel_shipment',
                [
                    'label' => __('Cancel'),
                    'class' => 'cancel',
                    'onclick' => "deleteConfirm('" . __(
                            'Are you sure you want to cancel a shipment?'
                        ) . "', '" . $this->getCancelUrl() . "')",
                ]
            );
        }
    }

    public function getCancelUrl(): string
    {
        return $this->getUrl('sales/shipment/cancel', [
            'shipment_id' => $this->getShipment()->getId(),
            'order_id' => $this->getShipment()->getOrderId(),
        ]);
    }
}
