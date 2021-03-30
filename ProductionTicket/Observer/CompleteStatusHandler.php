<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer;

use Labelin\ProductionTicket\Api\Data\ProductionTicketRepositoryInterface;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\Sales\Model\Order;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class CompleteStatusHandler implements ObserverInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ProductionTicketRepositoryInterface */
    protected $productionTicketRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var Order */
    protected $order;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductionTicketRepositoryInterface $productionTicketRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->productionTicketRepository = $productionTicketRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(Observer $observer): self
    {
        /** @var ProductionTicket $productionTicket */
        $productionTicket = $observer->getData('object');

        if (!$productionTicket) {
            return $this;
        }

        $this->order = $this->orderRepository->get($productionTicket->getOrderId());

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $productionTicket->getOrderId(), 'eq')
            ->addFilter('status', 0, 'eq')
            ->create();

        if (!$this->productionTicketRepository->getList($searchCriteria)->getTotalCount()
            && $this->compareOrderAndProductionTicketItemsQty()
        ) {
            $this->order->markAsReadyToShip();
            $this->orderRepository->save($this->order);
        }

        return $this;
    }

    protected function compareOrderAndProductionTicketItemsQty(): bool
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $this->order->getId())
            ->create();
        $productionTicketItemsCount = $this->productionTicketRepository->getList($searchCriteria)->getTotalCount();
        $orderItemsCount = count($this->order->getAllVisibleItems());

        return $productionTicketItemsCount === $orderItemsCount;
    }
}
