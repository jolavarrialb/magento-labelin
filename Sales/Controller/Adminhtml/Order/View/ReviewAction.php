<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order\View;

use Labelin\Sales\Model\Order;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;

class ReviewAction extends Action
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

        $order = $this->orderRepository->get($orderId);
        $order->setStatus(Order::STATUS_REVIEW);

        $this->orderRepository->save($order);

        $this->messageManager->addSuccessMessage(__('You put the order on review.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
