<?php

declare(strict_types=1);

namespace Labelin\Sales\Cron\Order;

use Labelin\Sales\Helper\Config\Overdue as OverdueConfigHelper;
use Labelin\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Psr\Log\LoggerInterface;

class OverdueStatusHandler
{
    protected const DATE_TIME_FORMAT = 'jS F Y';

    /** @var LoggerInterface */
    protected $logger;

    /** @var OverdueConfigHelper */
    protected $overdueConfigHelper;

    /** @var OrderRepositoryInterface */
    protected $orderCollectionFactory;

    /** @var OrderRepository */
    protected $orderRepository;

    /** @var \DateTime */
    protected $dateTime;

    /** @var Order */
    protected $order;

    public function __construct(
        LoggerInterface $logger,
        OverdueConfigHelper $overdueConfigHelper,
        OrderCollectionFactory $orderCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        \DateTime $dateTime,
        Order $order
    ) {
        $this->logger = $logger;
        $this->overdueConfigHelper = $overdueConfigHelper;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->dateTime = $dateTime;
        $this->order = $order;
    }

    public function execute(): self
    {
        $orderCollection = $this->getOrderCollection();

        if ($orderCollection->count() === 0) {
            $this->logger->info(sprintf(
                __('Orders for overdue not found. %s'),
                $this->dateTime->format(static::DATE_TIME_FORMAT)
            ));

            return $this;
        }

        foreach ($orderCollection as $order) {
            /** @var Order $order */

            try {
                $order->markAsOverdue();
                $this->orderRepository->save($order);

                $this->logger->info(sprintf(
                    __('Order with increment id: %s was set into overdue status %s'),
                    $order->getIncrementId(),
                    $this->dateTime->format(static::DATE_TIME_FORMAT)
                ));
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $this;
    }

    protected function getOverdueDateTime(): \DateTime
    {
        return $this->dateTime->modify(sprintf('- %s days midnight', $this->overdueConfigHelper->getOverdueDays()));
    }

    protected function getOrderCollection(): OrderCollection
    {
        return $this
            ->initOrderCollection()
            ->addFieldToFilter('status', ['in' => $this->order->getOverdueAvailableStatuses()])
            ->addFieldToFilter('created_at', ['lteq' => $this->getOverdueDateTime()]);
    }

    protected function initOrderCollection(array $data = []): OrderCollection
    {
        return $this->orderCollectionFactory->create($data);
    }
}
