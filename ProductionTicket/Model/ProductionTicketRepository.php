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
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class ProductionTicketRepository implements ProductionTicketRepositoryInterface
{
    /** @var ProductionTicketResourceModel */
    protected $resource;

    /** @var ObjectManager */
    protected $objectManager;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    public function __construct(
        ProductionTicketResourceModel $resource,
        ObjectManager $objectManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->objectManager = $objectManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * @param ProductionTicketInterface $productionTicket
     *
     * @return ProductionTicketInterface
     * @throws CouldNotSaveException
     */
    public function save(ProductionTicketInterface $productionTicket): ProductionTicketInterface
    {
        try {
            $this->resource->save($productionTicket);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the production ticket: %1',
                $exception->getMessage()
            ));
        }

        return $productionTicket;
    }

    /**
     * @param int $entityId
     *
     * @return ProductionTicketInterface
     * @throws NoSuchEntityException
     */
    public function get(int $entityId): ProductionTicketInterface
    {
        $productionTicket = $this->objectManager->create(ProductionTicket::class);
        $this->resource->load($productionTicket, $entityId);

        if (!$productionTicket->getId()) {
            throw new NoSuchEntityException(__('Production ticket with id "%1" does not exist.', $entityId));
        }

        return $productionTicket;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): ProductionTicketSearchResultsInterface
    {
        $collection = $this->objectManager->create(Collection::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->objectManager->create(ProductionTicketSearchResultsInterface::class);
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param ProductionTicketInterface $productionTicket
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ProductionTicketInterface $productionTicket): bool
    {
        try {
            $this->resource->delete($productionTicket);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Production Ticket: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @param int $entityId
     *
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->get($entityId));
    }
}
