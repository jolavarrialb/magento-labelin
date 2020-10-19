<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order\Item;

use Labelin\Sales\Model\Order\Item;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

class GuestCheckoutUpdateArtwork extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var EventManager */
    protected $eventManager;

    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        EventManager $eventManager
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->eventManager = $eventManager;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if (!$this->isValidRequestItem()) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $orderItem */
        $orderItem = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));

        if ($this->isValidFile()) {
            $this->eventManager->dispatch('labelin_sales_order_item_guest_artwork_update', [
                'item' => $orderItem,
                'comment' => $this->getRequest()->getParam('comment'),
            ]);

            return $this->saveOrderItemAndRedirect($orderItem);
        }

        if ($this->isRequestApprove()) {
            $this->eventManager->dispatch('labelin_sales_order_item_guest_artwork_approve', [
                'item' => $orderItem,
            ]);

            return $this->saveOrderItemAndRedirect($orderItem);
        }

        if ($this->isRequestDecline()) {
            $this->eventManager->dispatch('labelin_sales_order_item_guest_artwork_decline', [
                'item' => $orderItem,
                'comment' => $this->getRequest()->getParam('comment'),
            ]);

            return $this->saveOrderItemAndRedirect($orderItem);
        }

        $this->messageManager->addErrorMessage(__('Please upload file.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function isValidRequestItem(): bool
    {
        return (bool)$this->getRequest()->getParam('item_id');
    }

    protected function isValidFile(): bool
    {
        $file = $this->getRequest()->getFiles()->get('artwork');

        return $file['size'] !== 0;
    }

    protected function isRequestApprove(): bool
    {
        return (bool)$this->getRequest()->getParam('approve');
    }

    protected function isRequestDecline(): bool
    {
        return (bool)$this->getRequest()->getParam('decline');
    }

    /**
     * @param Item $item
     *
     * @return ResponseInterface|ResultInterface
     */
    protected function saveOrderItemAndRedirect(Item $item)
    {
        try {
            $this->orderItemRepository->save($item);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
