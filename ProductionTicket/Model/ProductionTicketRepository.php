<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model;

use Labelin\ProductionTicket\Api\Data\ProductionTicketInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketRepositoryInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketResourceInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketSearchResultsInterface;
use Labelin\ProductionTicket\Api\Data\ProductionTicketSearchResultsInterfaceFactory;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicketFactory as ProductionTicketResourceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductionTicketRepository implements ProductionTicketRepositoryInterface
{
    /** @var ProductionTicketResourceFactory */
    protected $resourceFactory;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ProductionTicketFactory */
    protected $productionTicketFactory;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var ProductionTicketSearchResultsInterfaceFactory */
    protected $searchResultsFactory;


    public function __construct(
        ProductionTicketFactory $productionTicketFactory,
        ProductionTicketResourceFactory $resourceFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ProductionTicketSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->productionTicketFactory = $productionTicketFactory;
        $this->resourceFactory = $resourceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
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
            $this->getResourceModel()->save($productionTicket);
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
        $productionTicket = $this->getModel();
        $this->getResourceModel()->load($productionTicket, $entityId);

        if (!$productionTicket->getId()) {
            throw new NoSuchEntityException(__('Production ticket with id "%1" does not exist.', $entityId));
        }

        return $productionTicket;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): ProductionTicketSearchResultsInterface
    {
        $collection = $this->getCollection();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->getSearchResults();
        $searchResults->setSearchCriteria($searchCriteria);

        $searchResults->setItems($collection->getItems());
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
            $this->getResourceModel()->delete($productionTicket);
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

    protected function getModel(array $data = []): ProductionTicketInterface
    {
        return $this->productionTicketFactory->create($data);
    }

    protected function getResourceModel(array $data = []): ProductionTicketResourceInterface
    {
        return $this->resourceFactory->create($data);
    }

    protected function getCollection(array $data = []): Collection
    {
        return $this->collectionFactory->create($data);
    }

    protected function getSearchResults(array $data = [])
    {
        return $this->searchResultsFactory->create($data);
    }
}
