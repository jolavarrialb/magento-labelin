<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer\Order\Item;

use Labelin\ProductionTicket\Api\Data\ProductionTicketSearchResultsInterface;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ProductionTicketRepository;
use Labelin\Sales\Helper\Product\Premade;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResults;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

abstract class AbstractItemInProduction
{
    /** @var Order */
    protected $order;

    /** @var Premade */
    protected $premadeHelper;

    /** @var ProductionTicketRepository */
    protected $productionTicketRepository;

    /** @var ProductionTicket */
    protected $productionTicket;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    public function __construct(
        ProductionTicketRepository $productionTicketRepository,
        ProductionTicket $productionTicket,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Premade $premadeHelper
    ) {
        $this->productionTicketRepository = $productionTicketRepository;
        $this->productionTicket = $productionTicket;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        $this->premadeHelper = $premadeHelper;
    }

    protected function getLabel(): string
    {
        return sprintf(
            '%s_%s/%s',
            $this->order->getIncrementId(),
            ($this->getProductionTicketsByOrderId((int)$this->order->getId())->getTotalCount()) + 1,
            $this->order->getItemsCollection(['configurable', 'simple'], true)->getTotalCount()
        );
    }

    protected abstract function getArtwork(Item $item): string;

    /**
     * @param $orderItem
     * @param string $artwork
     *
     * @throws \Exception
     */
    protected function saveProductionTicket($orderItem, string $artwork = ''): void
    {
        $this->productionTicket
            ->setOrderItemId((int)$orderItem->getId())
            ->setOrderId((int)$this->order->getId())
            ->setOrderItemLabel($this->getLabel())
            ->setShape($orderItem->getShape())
            ->setType($orderItem->getProduct()->getName())
            ->setSize($orderItem->getSize())
            ->setArtwork($artwork)
            ->setApprovalDate($orderItem->getApprovalDate())
            ->setDesigner($this->getDesigner($orderItem))
            ->setMaterial($orderItem->getType())
            ->setIsComplete(false)
            ->setItemQty($this->getOrderItemQty($orderItem));

        $this->productionTicketRepository->save($this->productionTicket);
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

    protected function getDesigner(Item $orderItem): string
    {
        if ($this->premadeHelper->isPremade($orderItem)) {
            return $this->premadeHelper::PREMADE_DESIGNER;
        }

        return $this->order->getDesigner() ? $this->order->getDesigner()->getName() : '';
    }

    protected function getOrderItemQty(Item $orderItem): string
    {
        return sprintf('QTY: %s', $orderItem->getQtyOrdered())  ?? '';
    }
}
