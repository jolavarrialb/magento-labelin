<?php

declare(strict_types=1);

namespace Labelin\Sales\Observer\Order\Item;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Psr\Log\LoggerInterface;

class ReviewStatusHandler implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Artwork */
    protected $artworkHelper;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        Artwork $artworkHelper,
        LoggerInterface $logger
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->logger = $logger;
        $this->artworkHelper = $artworkHelper;
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

            if ($item->getData('is_reordered')) {
                $item->setData('is_designer_update_artwork', 1);
                $item->setData('is_artwork_approved', 1);
                $item->setData('artwork_approval_date', new \Zend_Db_Expr('NOW()'));
                $item->setData('artwork_status', $this->artworkHelper::ARTWORK_STATUS_APPROVE);
            }

            try {
                $this->orderItemRepository->save($item);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $this;
    }
}
