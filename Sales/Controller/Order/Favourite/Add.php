<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Order\Favourite;

use Labelin\Sales\Model\Order;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;

class Add extends Action
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(Context $context, OrderRepositoryInterface $orderRepository)
    {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
    }

    public function execute()
    {
        if (!$this->isValidRequestOrder()) {
            $this->messageManager->addErrorMessage(__('Please specify order.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Order $order */
        $order = $this->orderRepository->get($this->getRequest()->getParam('id'));

        try {
            $order->markAsFavourite();
            $this->orderRepository->save($order);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $this->messageManager->addSuccessMessage(__('Order was successfully added to your favourite list.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function isValidRequestOrder(): bool
    {
        return (bool)$this->getRequest()->getParam('id');
    }
}
