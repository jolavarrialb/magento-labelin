<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Observer;

use Labelin\ProductionTicket\Api\Data\ProductionTicketRepositoryInterface;
use Labelin\ProductionTicket\Model\Email\Sender\ProductionTicketSender;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class EmailHandler implements ObserverInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ProductionTicketRepositoryInterface */
    protected $productionTicketRepository;

    /** @var ProductionTicketSender */
    protected $sender;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductionTicketRepositoryInterface $productionTicketRepository,
        ProductionTicketSender $sender
    ) {
        $this->orderRepository = $orderRepository;
        $this->productionTicketRepository = $productionTicketRepository;
        $this->sender = $sender;
    }

    public function execute(Observer $observer): self
    {
        /** @var ProductionTicket $productionTicket */
        $productionTicket = $observer->getData('object');

        if (!$productionTicket || !$productionTicket->getOrder()) {
            return $this;
        }

        $this->sender->send($productionTicket);

        return $this;
    }
}
