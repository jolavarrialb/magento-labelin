<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicketResource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class ProductionTicket extends AbstractModel implements IdentityInterface, ProductionTicketInterface
{
    const CACHE_TAG = 'labelin_production_ticket';

    protected function _construct()
    {
        $this->_init(ProductionTicketResource::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(ProductTicketInterface::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId(int $orderId)
    {
        return $this->setData(ProductTicketInterface::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderItemId()
    {
        return $this->getData(ProductTicketInterface::ORDER_ITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderItemId(int $orderItemId)
    {
        return $this->setData(ProductTicketInterface::ORDER_ITEM_ID, $orderItemId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderItemLabel()
    {
        return $this->getData(ProductTicketInterface::ORDER_ITEM_LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setOrderItemLabel(string $orderItemLabel)
    {
        return $this->setData(ProductTicketInterface::ORDER_ITEM_LABEL, $orderItemLabel);
    }

    /**
     * @inheritDoc
     */
    public function getShape()
    {
        return $this->getData(ProductTicketInterface::SHAPE);
    }

    /**
     * @inheritDoc
     */
    public function setShape(string $shape)
    {
        return $this->setData(ProductTicketInterface::SHAPE, $shape);
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->getData(ProductTicketInterface::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type)
    {
        return $this->setData(ProductTicketInterface::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return $this->getData(ProductTicketInterface::SIZE);
    }

    /**
     * @inheritDoc
     */
    public function setSize(string $size)
    {
        return $this->setData(ProductTicketInterface::SIZE, $size);
    }

    /**
     * @inheritDoc
     */
    public function getArtwork()
    {
        return $this->getData(ProductTicketInterface::ARTWORK);
    }

    /**
     * @inheritDoc
     */
    public function setArtwork(string $artwork)
    {
        return $this->setData(ProductTicketInterface::ARTWORK, $artwork);
    }

    /**
     * @inheritDoc
     */
    public function getApprovalDate()
    {
        return $this->getData(ProductTicketInterface::APPROVAL_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setApprovalDate(string $approvalDate)
    {
        return $this->setData(ProductTicketInterface::APPROVAL_DATE, $approvalDate);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(ProductTicketInterface::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(bool $status)
    {
        return $this->setData(ProductTicketInterface::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(ProductTicketInterface::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(ProductTicketInterface::UPDATED_AT);
    }
}
