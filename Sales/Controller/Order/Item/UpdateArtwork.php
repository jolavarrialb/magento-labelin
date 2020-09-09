<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Order\Item;

use Labelin\Sales\Exception\MaxArtworkDeclineAttemptsReached;
use Labelin\Sales\Model\Order\Item;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class UpdateArtwork extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var Session */
    protected $customerSession;

    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository,
        Session $customerSession
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if (!$this->getRequest()->getParam('approve') && !$this->getRequest()->getParam('comment')) {
            $this->messageManager->addErrorMessage(__('For decline necessary comment. Please add notes when decline.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $orderItem */
        $orderItem = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));

        if ($this->getRequest()->getParam('approve')) {
            $this->processOrderItemApprove($orderItem);
        } else {
            $this->processOrderItemDecline($orderItem);
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function processOrderItemApprove(Item $item): self
    {
        try {
            $item->approveArtwork();
            $this->orderItemRepository->save($item);

        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        }

        $this->messageManager->addSuccessMessage(__('Artwork was successfully approved.'));

        return $this;
    }

    protected function processOrderItemDecline(Item $item): self
    {
        try {
            $item->incrementArtworkDeclinesCount();
            $this->orderItemRepository->save($item);

            $order = $item->getOrder();

            if (!$order) {
                return $this;
            }

            $order->addStatusToHistory($order->getStatus(), sprintf(
                    '%s: %s',
                    $this->customerSession->getCustomer()->getName(),
                    $this->getRequest()->getParam('comment')
                )
            );

            $this->orderRepository->save($order);

        } catch (MaxArtworkDeclineAttemptsReached $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        }

        $this->messageManager->addNoticeMessage(__('Artwork was declined. Please wait until designer will update artwork'));

        return $this;
    }
}
