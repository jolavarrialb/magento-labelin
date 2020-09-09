<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Order\Item;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
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
        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $file = $this->getRequest()->getFiles()->get('artwork');

        if ($file['size'] === 0) {
            $this->messageManager->addErrorMessage(__('Please upload file.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }


        // toDo process image file (update artwork)

        $this->messageManager->addSuccessMessage(__('Artwork was successfully uploaded.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
