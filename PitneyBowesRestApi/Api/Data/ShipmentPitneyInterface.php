<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

use DateTime;

interface ShipmentPitneyInterface
{
    public const ENTITY_ID = 'entity_id';

    public const ORDER_ID = 'order_id';

    public const ORDER_ITEM_ID = 'order_item_id';

    public const RESPONSE = 'response';

    public const TRACKING_ID = 'tracking_id';

    public const LABEL_LINK ='label_link';

    public const IS_CANCELED = 'is_canceled';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    public function getOrderId(): int;

    public function setOrderId(int $orderId): self;

    public function getOrderItemId(): int;

    public function setOrderItemId(int $itemId): self;

    public function getResponse(): string;

    public function setResponse(string $response): self;

    public function getTrackingId(): int;

    public function setTrackingId(int $trackingId): self;

    public function getLabelLink(): string;

    public function setLabelLink(string $labelLink): self;

    public function getIsCanceled(): string;

    public function setIsCanceled(bool $labelLink): self;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}
