<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket as ProductionTicketResource;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class ProductionTicket extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'labelin_production_ticket';

    protected $dataObjectHelper;

    protected $_eventPrefix = 'labelin_productionticket_productionticket';

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

    public function getDataModel(): ProductionTicketInterface
    {
        $productionTicketData = $this->getData();

        $productionTicketModelObject = $this->objectManager->create(ProductionTicketInterface::class);
        $this->dataObjectHelper->populateWithArray(
            $productionTicketModelObject,
            $productionTicketData,
            ProductionTicketInterface::class
        );

        return $productionTicketModelObject;
    }
}
