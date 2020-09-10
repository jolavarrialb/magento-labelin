<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Order\Item;

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

class UploadArtwork extends Action
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

        if (!$this->isValidRequestFile()) {
            $this->messageManager->addErrorMessage(__('Please upload file.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $orderItem */
        $orderItem = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));

        // toDo process image file (update artwork)
        $this->processOrderItem($orderItem);

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function processOrderItem(Item $item): self
    {
        try {
            $this->orderItemRepository->save($item);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());

            return $this;
        }

        $this->messageManager->addSuccessMessage(__('Artwork was successfully uploaded.'));

        return $this;
    }

    protected function isValidRequestItem(): bool
    {
        return (bool)$this->getRequest()->getParam('item_id');
    }

    protected function isValidRequestFile(): bool
    {
        $file = $this->getRequest()->getFiles()->get('artwork');

        return $file['size'] !== 0;
    }
}
