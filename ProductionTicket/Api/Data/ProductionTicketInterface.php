<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Api\Data;

interface ProductionTicketInterface
{
    const ENTITY_ID = 'entity_id';

    const ORDER_ID = 'order_id';

    const ORDER_ITEM_ID = 'order_item_id';

    const ORDER_ITEM_LABEL = 'order_item_label';

    const SHAPE = 'shape';

    const TYPE = 'type';

    const SIZE = 'size';

    const ARTWORK = 'artwork';

    const APPROVAL_DATE = 'approval_date';

    const STATUS = 'status';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    /**
     * Gets the ID for the productTicket
     *
     * @return int
     */
    public function getEntityId();

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
    public function getOrderId();

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
    public function getOrderItemId();

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
    public function getOrderItemLabel();

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
    public function getShape();

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
    public function getType();

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
    public function getSize();

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
    public function getArtwork();

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
     * @return string
     */
    public function getApprovalDate();

    /**
     * Sets order item ticket Approve Date
     *
     * @param string $approvalDate
     * @return $this
     */
    public function setApprovalDate(string $approvalDate);

    /**
     * Gets Product ticket ready status
     *
     * @return bool
     */
    public function getStatus();

    /**
     * Sets Product ticket ready status
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();
}
