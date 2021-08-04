<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Email\Sender;

use Labelin\PitneyBowesShipping\Model\Carrier\AbstractPitneyBowesCarrier;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Shipment;

class TrackInfoSender extends Sender
{
    public function send(Shipment\Track $tracking, Shipment $shipment): void
    {
        if (!$tracking->getTrackNumber() || !$shipment->getId()) {
            return;
        }

        $transport = [
            'shipment' => $shipment,
            'order' => $shipment->getOrder(),
            'order_number' => $shipment->getOrder()->getIncrementId(),
            'tracking_link' => AbstractPitneyBowesCarrier::TRACKING_URL . $tracking->getTrackNumber(),
            'tracking_number' => $tracking->getTrackNumber(),
            'customer_name' => $shipment->getOrder()->getCustomerName(),
            'template_subject' => __('Your order has shipped #%1', $shipment->getOrder()->getIncrementId())->render(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($shipment->getOrder());
    }
}
