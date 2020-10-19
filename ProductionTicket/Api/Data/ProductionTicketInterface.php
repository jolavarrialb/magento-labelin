<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Api\Data;

use DateTime;

interface ProductionTicketInterface
{
    public const ENTITY_ID = 'entity_id';

    public const ORDER_ID = 'order_id';

    public const ORDER_ITEM_ID = 'order_item_id';

    public const ORDER_ITEM_LABEL = 'order_item_label';

    public const SHAPE = 'shape';

    public const TYPE = 'type';

    public const SIZE = 'size';

    public const ARTWORK = 'artwork';

    public const APPROVAL_DATE = 'approval_date';

    public const STATUS = 'status';

    public const DESIGNER = 'designer';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * Sets entity ID
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId(int $entityId);

    /**
     * Gets order ID
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Sets order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId);

    /**
     * Gets order item ID
     *
     * @return int
     */
    public function getOrderItemId(): int;

    /**
     * Sets order item ID
     *
     * @param int $orderItemId
     * @return $this
     */
    public function setOrderItemId(int $orderItemId);

    /**
     * Gets the order item Label for the order item
     *
     * @return string|null
     */
    public function getOrderItemLabel(): ?string;

    /**
     * Sets the order item Label for the order item
     *
     * @param string $orderItemLabel
     * @return $this
     */
    public function setOrderItemLabel(string $orderItemLabel);

    /**
     * Gets the order item param Shape for the order item
     *
     * @return string|null
     */
    public function getShape(): ?string;

    /**
     * Sets the order item param Shape for the order item
     *
     * @param string $shape
     * @return $this
     */
    public function setShape(string $shape);

    /**
     * Gets the order item param Type for the order item
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Sets the order item param Type for the order item
     *
     * @param string $type
     * @return mixed
     */
    public function setType(string $type);

    /**
     * Gets the order item param Size for the order item
     *
     * @return string|null
     */
    public function getSize(): ?string;

    /**
     * Sets the order item param Size for the order item
     *
     * @param string $size
     * @return $this
     */
    public function setSize(string $size);

    /**
     * Gets the order item Artwork
     *
     * @return string
     */
    public function getArtwork(): string;

    /**
     * Sets the order item Artwork
     *
     * @param string $artwork
     * @return $this
     */
    public function setArtwork(string $artwork);

    /**
     * Gets order item ticket Approve Date
     *
     * @return DateTime
     */
    public function getApprovalDate(): DateTime;

    /**
     * Sets order item ticket Approve Date
     *
     * @param DateTime $approvalDate
     * @return $this
     */
    public function setApprovalDate(DateTime $approvalDate);

    /**
     * Gets Product ticket ready status
     *
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * Sets Product ticket ready status
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status);

    public function getDesigner(): string;

    /**
     * @param string $designer
     *
     * @return mixed
     */
    public function setDesigner(string $designer);

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime;
}
