<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order\Item;

use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Psr\Log\LoggerInterface;

class ReviewStatusHandler implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository, LoggerInterface $logger)
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
    }

    public function execute(Observer $observer): self
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        foreach ($order->getAllItems() as $item) {
            /** @var Order\Item $item */
            if ($item->getProductType() !== Configurable::TYPE_CODE) {
                continue;
            }

            $item->setData('artwork_approval_by_designer_date', new \Zend_Db_Expr('NOW()'));

            try {
                $this->orderItemRepository->save($item);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $this;
    }
}
