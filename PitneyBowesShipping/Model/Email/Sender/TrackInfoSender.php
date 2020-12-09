<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Email\Sender;

use Labelin\PitneyBowesShipping\Model\Carrier\AbstractPitneyBowesCarrier;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Shipment;

class TrackInfoSender extends Sender
{
    public function send(string $trackingNumber, Shipment $shipment): void
    {
        if (!$trackingNumber || !$shipment->getId()) {
            return;
        }

        $transport = [
            'shipment' => $shipment,
            'order' => $shipment->getOrder(),
            'tracking_link' => AbstractPitneyBowesCarrier::TRACKING_URL . $trackingNumber,
            'tracking_number' => $trackingNumber,
            'customer_name' => $shipment->getOrder()->getCustomerName(),
            'template_subject' => __('Tracking info for order #%1', $shipment->getOrder()->getIncrementId()),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($shipment->getOrder());
    }
}
