<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Plugin\Block\Adminhtml\Order\Shipment\View;

use Magento\Framework\Exception\LocalizedException;
use Magento\Shipping\Block\Adminhtml\View\Form;

class PrintShippingLabelButton
{
    /**
     * @param Form $subject
     * @param callable $proceed
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function aroundGetPrintLabelButton(Form $subject, callable $proceed): string
    {
        $data['shipment_id'] = $subject->getShipment()->getId();
        $url = $subject->getUrl('adminhtml/order_shipment/printLabel', $data);

        return $subject->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            ['label' => __('Print Shipping Label'), 'onclick' => 'window.open(\'' . $url . '\', \'_blank\')']
        )->toHtml();
    }
}
