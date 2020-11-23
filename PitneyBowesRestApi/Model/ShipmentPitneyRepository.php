<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyRepositoryInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyResourceInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneySearchResultsInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneySearchResultsInterfaceFactory;
use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitney\Collection;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitneyResourceFactory;
use Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitney\CollectionFactory;

class ShipmentPitneyRepository implements ShipmentPitneyRepositoryInterface
{
    /** @var ShipmentPitneyFactory */
    protected $shipmentPitneyFactory;

    /** @var ShipmentPitneyResourceFactory */
    protected $resourceFactory;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var ShipmentPitneySearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    public function __construct(
        ShipmentPitneyFactory $shipmentPitneyFactory,
        ShipmentPitneyResourceFactory $resourceFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ShipmentPitneySearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->shipmentPitneyFactory = $shipmentPitneyFactory;
        $this->resourceFactory = $resourceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function save(ShipmentPitneyInterface $shipmentPitney): ShipmentPitneyInterface
    {
        try {
            $this->getResourceModel()->save($shipmentPitney);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the production ticket: %1',
                $exception->getMessage()
            ));
        }

        return $shipmentPitney;
    }

    /**
     * @param int $entityId
     *
     * @return ShipmentPitneyInterface
     * @throws NoSuchEntityException
     */
    public function get(int $entityId): ShipmentPitneyInterface
    {
        $shipmentPitney = $this->getModel();
        $this->getResourceModel()->load($shipmentPitney, $entityId);

        if (!$shipmentPitney->getId()) {
            throw new NoSuchEntityException(__('Shipment Pitney with id "%1" does not exist.', $entityId));
        }

        return $shipmentPitney;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShipmentPitneySearchResultsInterface|SearchResult
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
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
     * @param ShipmentPitneyInterface $shipmentPitney
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShipmentPitneyInterface $shipmentPitney): bool
    {
        try {
            $this->getResourceModel()->delete($shipmentPitney);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Shipment Pitney: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * @param int $entityId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $entityId): bool
    {
        $this->delete($this->get($entityId));
    }

    protected function getModel(array $data = []): ShipmentPitneyInterface
    {
        return $this->shipmentPitneyFactory->create($data);
    }

    protected function getResourceModel(array $data = []): ShipmentPitneyResourceInterface
    {
        return $this->resourceFactory->create($data);
    }

    protected function getCollection(array $data = []): Collection
    {
        return $this->collectionFactory->create($data);
    }

    /**
     * @param array $data
     * @return SearchResults|ShipmentPitneySearchResultsInterface
     */
    protected function getSearchResults(array $data = [])
    {
        return $this->searchResultsFactory->create($data);
    }
}
