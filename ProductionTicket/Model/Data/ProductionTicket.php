<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Data;

use DateTime;
use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class ProductionTicket extends AbstractExtensibleObject implements ProductionTicketInterface
{
    public function setEntityId(int $entityId): self
    {
        return $this->setData(ProductionTicketInterface::ENTITY_ID, $entityId);
    }

    public function getOrderId(): int
    {
        return $this->_get(ProductionTicketInterface::ORDER_ID);
    }

    public function setOrderId(int $orderId): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ID, $orderId);
    }

    public function getOrderItemId(): int
    {
        return $this->_get(ProductionTicketInterface::ORDER_ITEM_ID);
    }

    public function setOrderItemId(int $orderItemId): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ITEM_ID, $orderItemId);
    }

    public function getOrderItemLabel(): ?string
    {
        return $this->_get(ProductionTicketInterface::ORDER_ITEM_LABEL);
    }

    public function setOrderItemLabel(string $orderItemLabel): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ITEM_LABEL, $orderItemLabel);
    }

    public function getShape(): ?string
    {
        return $this->_get(ProductionTicketInterface::SHAPE);
    }

    public function setShape(string $shape): self
    {
        return $this->setData(ProductionTicketInterface::SHAPE, $shape);
    }

    public function getType(): ?string
    {
        return $this->_get(ProductionTicketInterface::TYPE);
    }

    public function setType(string $type): self
    {
        return $this->setData(ProductionTicketInterface::TYPE, $type);
    }

    public function getSize(): ?string
    {
        return $this->_get(ProductionTicketInterface::SIZE);
    }

    public function setSize(string $size): self
    {
        return $this->setData(ProductionTicketInterface::SIZE, $size);
    }

    public function getArtwork(): string
    {
        return $this->_get(ProductionTicketInterface::ARTWORK);
    }

    public function setArtwork(string $artwork): self
    {
        return $this->setData(ProductionTicketInterface::ARTWORK, $artwork);
    }

    public function getApprovalDate(): DateTime
    {
        return $this->_get(ProductionTicketInterface::APPROVAL_DATE);
    }

    public function setApprovalDate(DateTime $approvalDate): self
    {
        return $this->setData(ProductionTicketInterface::APPROVAL_DATE, $approvalDate);
    }

    public function getStatus(): bool
    {
        return $this->_get(ProductionTicketInterface::STATUS);
    }

    public function setStatus(bool $status): self
    {
        return $this->setData(ProductionTicketInterface::STATUS, $status);
    }

    public function getCreatedAt(): DateTime
    {
        return $this->_get(ProductionTicketInterface::CREATED_AT);
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->_get(ProductionTicketInterface::UPDATED_AT);
    }
}
