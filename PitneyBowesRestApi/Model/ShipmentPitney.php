<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model;

use DateTime;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyInterface;
use Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitneyResource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class ShipmentPitney extends AbstractModel implements ShipmentPitneyInterface, IdentityInterface
{
    public const CACHE_TAG = 'labelin_shipment_pitney';

    protected function _construct()
    {
        $this->_init(ShipmentPitneyResource::class);
    }

    public function getOrderId(): string
    {
        return $this->getData(ShipmentPitneyInterface::ORDER_ID);
    }

    public function setOrderId(int $orderId): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::ORDER_ID, $orderId);
    }

    public function getShipmentId(): string
    {
        return $this->getData(ShipmentPitneyInterface::SHIPMENT_ID);
    }

    public function setShipmentId(int $shipmentId): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::SHIPMENT_ID, $shipmentId);
    }

    public function getResponse(): string
    {
        return $this->getData(ShipmentPitneyInterface::RESPONSE);
    }

    public function setResponse(string $response): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::RESPONSE, $response);
    }

    public function getTrackingId(): string
    {
        return $this->getData(ShipmentPitneyInterface::TRACKING_ID);
    }

    public function setTrackingId(string $trackingId): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::TRACKING_ID, $trackingId);
    }

    public function getCreatedAt(): DateTime
    {
        return $this->getData(ShipmentPitneyInterface::CREATED_AT);
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->getData(ShipmentPitneyInterface::UPDATED_AT);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getLabelLink(): string
    {
        return $this->getData(ShipmentPitneyInterface::LABEL_LINK);
    }

    public function setLabelLink(string $labelLink): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::LABEL_LINK, $labelLink);
    }

    public function getIsCanceled(): string
    {
        return $this->getData(ShipmentPitneyInterface::IS_CANCELED);
    }

    public function setIsCanceled(bool $isCanceled): ShipmentPitneyInterface
    {
        return $this->setData(ShipmentPitneyInterface::IS_CANCELED, $isCanceled);
    }
}
