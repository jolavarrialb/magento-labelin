<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketRepositoryInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketSearchResultsInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket as ProductionTicketResourceModel;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class ProductionTicketRepository implements ProductionTicketRepositoryInterface
{
    /** @var ProductionTicketResourceModel */
    protected $resource;

    /** @var ProductionTicketFactory */
    protected $productionTicketFactory;

    /** @var ObjectManager */
    protected $objectManager;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ExtensibleDataObjectConverter  */
    protected $extensibleDataObjectConverter;

    public function __construct(
        ProductionTicketResourceModel $resource,
        ProductionTicketFactory $productionTicketFactory,
        ObjectManager $objectManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->productionTicketFactory = $productionTicketFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->objectManager = $objectManager; //@TODO DELL IF NOT USE ALEX !!!
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * @param ProductionTicketInterface $productionTicket
     * @return ProductionTicketInterface
     * @throws CouldNotSaveException
     */
    public function save(ProductionTicketInterface $productionTicket): ProductionTicketInterface
    {
        $productionTicketData = $this->extensibleDataObjectConverter->toNestedArray(
            $productionTicket,
            [],
            ProductionTicketInterface::class
        );

        $productionTicketModel = $this->productionTicketFactory->create()->setData($productionTicketData);

        try {
            $this->resource->save($productionTicketModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productionTicket: %1',
                $exception->getMessage()
            ));
        }
        return $productionTicketModel->getDataModel();
    }

    /**
     * @param int $entityId
     * @return ProductionTicketInterface
     * @throws NoSuchEntityException
     */
    public function get(int $entityId): ProductionTicketInterface
    {
        $productionTicket = $this->productionTicketFactory->create();
        $this->resource->load($productionTicket, $entityId);
        if (!$productionTicket->getId()) {
            throw new NoSuchEntityException(__('ProductionTicket with id "%1" does not exist.', $entityId));
        }

        return $productionTicket->getDataModel();
    }

    public function getList(SearchCriteriaInterface $searchCriteria): ProductionTicketSearchResultsInterface
    {
        $collection = $this->objectManager->create(Collection::class);
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            ProductionTicketInterface::class
        );
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->objectManager->create(ProductionTicketSearchResultsInterface::class);
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(ProductionTicketInterface $productionTicket): bool
    {

        try {
            $productionTicketModel = $this->productionTicketFactory->create();
            $this->resource->load($productionTicketModel, $productionTicket->getEntityId());
            $this->resource->delete($productionTicketModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductionTicket: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @param int $entityId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->get($entityId));
    }
}
