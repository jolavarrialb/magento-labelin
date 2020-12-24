<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Block\Adminhtml\Order\View;

use Magento\Framework\UrlInterface;
use Magento\Sales\Block\Adminhtml\Order\View as MageView;
use Magento\Sales\Model\Order\Shipment;

class PrintShippingLabelButton
{
    /** @var UrlInterface */
    protected $url;

    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    public function beforeSetLayout(MageView $subject): void
    {
        $shipments = $subject->getOrder()->getShipmentsCollection();

        if ($shipments->getSize() === 0) {
            return;
        }

        if (!$shipments->getFirstItem()->getShippingLabel()) {
            return;
        }

        /** @var Shipment $shipment */
        $shipment = $shipments->getFirstItem();

        $data['order_id'] = $subject->getOrder()->getId();
        $data['shipment'] = $shipment;
        $data['shipment_id'] = $shipment->getId();
        $data['tracking'] = $shipment->getTracksCollection();

        $url = $this->url->getUrl('adminhtml/order_shipment/printLabel', $data);

        $subject->addButton(
            'print_shipping_label',
            [
                'label' => __('Print Shipping Label'),
                'class' => __('Print Shipping Label'),
                'id' => 'order-view-print-shipping-label-button',
                'onclick' => 'window.open(\'' . $url . '\', \'_blank\')',
            ]
        );
    }
}
