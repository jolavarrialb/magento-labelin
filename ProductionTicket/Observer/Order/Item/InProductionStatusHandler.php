<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Api\Data\ProductionTicketSearchResultsInterface;
use Labelin\ProductionTicket\Model\Order\Item;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ProductionTicketRepository;
use Labelin\Sales\Helper\Artwork as ArtworkHelper;
use Labelin\Sales\Model\Order;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonHelper;

class InProductionStatusHandler implements ObserverInterface
{
    /** @var ProductionTicketRepository */
    protected $productionTicketRepository;

    /** @var ProductionTicket */
    protected $productionTicket;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var JsonHelper */
    protected $jsonHelper;

    public function __construct(
        ProductionTicketRepository $productionTicketRepository,
        ProductionTicket $productionTicket,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ArtworkHelper $artworkHelper,
        JsonHelper $jsonHelper
    ) {
        $this->productionTicketRepository = $productionTicketRepository;
        $this->productionTicket = $productionTicket;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        $this->artworkHelper = $artworkHelper;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer): self
    {
        /** @var Item $orderItem */
        $orderItem = $observer->getData('item');

        if (!$orderItem || !$orderItem->getProduct()) {
            return $this;
        }

        /** @var Order $order */
        $order = $orderItem->getOrder();

        if (!$order) {
            return $this;
        }

        $label = sprintf(
            '%s_%s/%s',
            $order->getIncrementId(),
            ($this->getProductionTicketsByOrderId((int)$order->getId())->getTotalCount()) + 1,
            $order->getItemsCollection(['configurable'])->getTotalCount()
        );

        $artwork = $this->artworkHelper->getArtworkProductOptionByItem($orderItem);

        $this->productionTicket
            ->setOrderItemId((int)$orderItem->getId())
            ->setOrderId((int)$order->getId())
            ->setOrderItemLabel($label)
            ->setShape($orderItem->getShape())
            ->setType($orderItem->getType())
            ->setSize($orderItem->getSize())
            ->setArtwork($artwork['value'])
            ->setApprovalDate($orderItem->getApprovalDate())
            ->setDesigner($order->getDesigner() ? $order->getDesigner()->getName() : '')
            ->setMaterial($orderItem->getProduct()->getName());

        $this->productionTicketRepository->save($this->productionTicket);

        return $this;
    }

    /**
     * @param int $orderId
     *
     * @return ProductionTicketSearchResultsInterface|SearchResults
     */
    protected function getProductionTicketsByOrderId(int $orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId, 'eq')
            ->create();

        return $this->productionTicketRepository->getList($searchCriteria);
    }
}
