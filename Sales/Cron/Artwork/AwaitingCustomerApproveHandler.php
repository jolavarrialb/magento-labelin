<?php

declare(strict_types=1);

namespace Labelin\Sales\Cron\Artwork;

use Labelin\Sales\Helper\Config\ArtworkAwaitingCustomerApprove as ArtworkAwaitingCustomerApproveHelper;
use Labelin\Sales\Model\Artwork\Email\Sender\AwaitingApproveSender;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Psr\Log\LoggerInterface;

class AwaitingCustomerApproveHandler
{
    /** @var ArtworkAwaitingCustomerApproveHelper */
    protected $awaitingCustomerApproveHelper;

    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var AwaitingApproveSender */
    protected $sender;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        ArtworkAwaitingCustomerApproveHelper $awaitingCustomerApproveHelper,
        OrderItemRepositoryInterface $orderItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AwaitingApproveSender $sender,
        LoggerInterface $logger
    ) {
        $this->awaitingCustomerApproveHelper = $awaitingCustomerApproveHelper;
        $this->orderItemRepository = $orderItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sender = $sender;
        $this->logger = $logger;
    }

    public function execute(): self
    {
        if (!$this->awaitingCustomerApproveHelper->isEnabled()) {
            return $this;
        }

        $fromDate = new \DateTime();
        $fromDate->modify(sprintf('- %s days', $this->awaitingCustomerApproveHelper->getDaysBeforeNotification()));

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('is_artwork_approved', 0, 'eq')
            ->addFilter('is_designer_notified_on_awaiting_customer_approve', 0, 'eq')
            ->addFilter(
                'artwork_approval_by_designer_date',
                $fromDate->format($this->awaitingCustomerApproveHelper::DATE_TIME_FORMAT),
                'lteq'
            )
            ->create();

        $collection = $this->orderItemRepository->getList($searchCriteria);

        if ($collection->getSize() === 0) {
            return $this;
        }

        foreach ($collection as $item) {
            /** @var Item $item */
            if ($this->sender->send($item)) {
                $item->setData('is_designer_notified_on_awaiting_customer_approve', 1);

                try {
                    $this->orderItemRepository->save($item);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }

        return $this;
    }
}
