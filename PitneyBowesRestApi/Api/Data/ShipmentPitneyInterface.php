<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

use DateTime;

interface ShipmentPitneyInterface
{
    public const ENTITY_ID = 'entity_id';

    public const ORDER_ID = 'order_id';

    public const SHIPMENT_ID = 'shipment_id';

    public const RESPONSE = 'response';

    public const TRACKING_ID = 'tracking_id';

    public const LABEL_LINK = 'label_link';

    public const IS_CANCELED = 'is_canceled';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    public function getOrderId(): string;

    public function setOrderId(string $orderId): self;

    public function getShipmentId(): string;

    public function setShipmentId(string $shipmentId): self;

    public function getResponse(): string;

    public function setResponse(string $response): self;

    public function getTrackingId(): string;

    public function setTrackingId(string $trackingId): self;

    public function getLabelLink(): string;

    public function setLabelLink(string $labelLink): self;

    public function getIsCanceled(): string;

    public function setIsCanceled(bool $labelLink): self;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}
