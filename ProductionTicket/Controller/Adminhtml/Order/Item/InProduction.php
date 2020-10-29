<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Order\Item;

use Labelin\ProductionTicket\Model\Order\Item;
use Labelin\Sales\Model\Order;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class InProduction extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $item */
        $item = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));
        /** @var Order $order */
        $order = $item->getOrder();

        try {
            $item->markAsInProduction();
            $this->orderItemRepository->save($item);

            if ($order->isAllItemsReadyForProduction()) {
                $order->markAsProduction();
                $this->orderRepository->save($order);
            }

        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $this->_eventManager->dispatch('labelin_order_item_production_status_after', ['item' => $item]);

        $this->messageManager->addSuccessMessage(__('Artwork was successfully moved to production.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
