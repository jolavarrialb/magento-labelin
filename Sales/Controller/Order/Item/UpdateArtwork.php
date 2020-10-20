<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Order\Item;

use Labelin\Sales\Exception\MaxArtworkDeclineAttemptsReached;
use Labelin\Sales\Helper\Artwork;
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
        if (!$this->isValidRequestItem()) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if ($this->isInValidRequestDecline()) {
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

        $this->_eventManager->dispatch('labelin_sales_order_item_artwork_update_status', [
                'item' => $item,
                'status' => Artwork::ARTWORK_STATUS_APPROVE]
        );

        $this->messageManager->addSuccessMessage(__('Artwork was successfully approved.'));

        return $this;
    }

    protected function processOrderItemDecline(Item $item): self
    {
        try {
            $order = $item->getOrder();

            if (!$order) {
                return $this;
            }

            $item->incrementArtworkDeclinesCount();
            $this->orderItemRepository->save($item);

            $order->addStatusToHistory($order->getStatus(), sprintf(
                '%s: %s',
                $this->customerSession->getCustomer()->getName(),
                $this->getRequest()->getParam('comment')
            ));

            $this->_eventManager->dispatch('labelin_order_item_decline_after', [
                'order_item' => $item,
                'comment'    => $this->getRequest()->getParam('comment'),
            ]);

            $this->orderRepository->save($order);
        } catch (MaxArtworkDeclineAttemptsReached $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        }

        $this->_eventManager->dispatch('labelin_sales_order_item_artwork_update_status', [
                'item' => $item,
                'status' => Artwork::ARTWORK_STATUS_DECLINE]
        );

        $this->messageManager->addNoticeMessage(
            __('Artwork was declined. Please wait until designer will update artwork')
        );

        return $this;
    }

    protected function isValidRequestItem(): bool
    {
        return (bool)$this->getRequest()->getParam('item_id');
    }

    protected function isInValidRequestDecline(): bool
    {
        return !$this->getRequest()->getParam('approve') && !$this->getRequest()->getParam('comment');
    }
}
