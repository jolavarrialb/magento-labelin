<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer;

use Labelin\ProductionTicket\Api\Data\ProductionTicketRepositoryInterface;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\Sales\Model\Order;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;

class CompleteStatusHandler implements ObserverInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ProductionTicketRepositoryInterface */
    protected $productionTicketRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

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

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $productionTicket->getOrderId(), 'eq')
            ->addFilter('status', 0, 'eq')
            ->create();

        if (!$this->productionTicketRepository->getList($searchCriteria)->getTotalCount()) {
            /** @var Order $order */
            $order = $this->orderRepository->get($productionTicket->getOrderId());
            $order->markAsReadyToShip();
            $this->orderRepository->save($order);
        }

        return $this;
    }
}
