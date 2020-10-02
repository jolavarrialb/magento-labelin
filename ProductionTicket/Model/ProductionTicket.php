<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use DateTime;
use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket as ProductionTicketResource;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class ProductionTicket extends AbstractModel implements IdentityInterface, ProductionTicketInterface
{
    public const CACHE_TAG = 'labelin_production_ticket';

    protected $dataObjectHelper;

    /** @var ObjectManager */
    protected $objectManager;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        ObjectManager $objectManager,
        ProductionTicketResource $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->objectManager = $objectManager;

        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init(ProductionTicketResource::class);
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getOrderId(): int
    {
        return $this->getData(ProductionTicketInterface::ORDER_ID);
    }

    public function setOrderId(int $orderId): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ID, $orderId);
    }

    public function getOrderItemId(): int
    {
        return $this->getData(ProductionTicketInterface::ORDER_ITEM_ID);
    }

    public function setOrderItemId(int $orderItemId): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ITEM_ID, $orderItemId);
    }

    public function getOrderItemLabel(): ?string
    {
        return $this->getData(ProductionTicketInterface::ORDER_ITEM_LABEL);
    }

    public function setOrderItemLabel(string $orderItemLabel): self
    {
        return $this->setData(ProductionTicketInterface::ORDER_ITEM_LABEL, $orderItemLabel);
    }

    public function getShape(): ?string
    {
        return $this->getData(ProductionTicketInterface::SHAPE);
    }

    public function setShape(string $shape): self
    {
        return $this->setData(ProductionTicketInterface::SHAPE, $shape);
    }

    public function getType(): ?string
    {
        return $this->getData(ProductionTicketInterface::TYPE);
    }

    public function setType(string $type): self
    {
        return $this->setData(ProductionTicketInterface::TYPE, $type);
    }

    public function getSize(): ?string
    {
        return $this->getData(ProductionTicketInterface::SIZE);
    }

    public function setSize(string $size): self
    {
        return $this->setData(ProductionTicketInterface::SIZE, $size);
    }

    public function getArtwork(): string
    {
        return $this->getData(ProductionTicketInterface::ARTWORK);
    }

    public function setArtwork(string $artwork): self
    {
        return $this->setData(ProductionTicketInterface::ARTWORK, $artwork);
    }

    public function getApprovalDate(): DateTime
    {
        return $this->getData(ProductionTicketInterface::APPROVAL_DATE);
    }

    public function setApprovalDate(DateTime $approvalDate): self
    {
        return $this->setData(ProductionTicketInterface::APPROVAL_DATE, $approvalDate);
    }

    public function getStatus(): bool
    {
        return $this->getData(ProductionTicketInterface::STATUS);
    }

    public function setStatus(bool $status): self
    {
        return $this->setData(ProductionTicketInterface::STATUS, $status);
    }

    public function getCreatedAt(): DateTime
    {
        return $this->getData(ProductionTicketInterface::CREATED_AT);
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->getData(ProductionTicketInterface::UPDATED_AT);
    }
}
