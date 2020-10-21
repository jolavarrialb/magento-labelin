<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Artwork;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Psr\Log\LoggerInterface;

class UpdateStatusHandler implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        OrderItemRepositoryInterface $orderItemRepository
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $item = $observer->getData('item');
        $status = $observer->getData('status');

        if (!$item || !$status) {
            return $this;
        }

        try {
            $item->setData(Item::ARTWORK_STATUS, $status);
            $this->orderItemRepository->save($item);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return $this;
        }

        return $this;
    }
}
