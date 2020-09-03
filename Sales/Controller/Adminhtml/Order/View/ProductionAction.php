<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order\View;

use Labelin\Sales\Model\Order;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;

class ProductionAction extends Action
{
    /*** @var OrderFactory */
    protected $orderFactory;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(Context $context, OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        if (!$orderId) {
            $this->messageManager->addErrorMessage(__('Please specify order.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        try {
            /** @var Order $order */
            $order = $this->orderRepository->get($orderId);

            $order->markAsProduction();

            $this->orderRepository->save($order);

        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $this->messageManager->addSuccessMessage(__('You put the order to production.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
