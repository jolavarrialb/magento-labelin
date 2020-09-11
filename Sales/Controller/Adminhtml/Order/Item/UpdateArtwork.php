<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order\Item;

use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Order\Item;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class UpdateArtwork extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var DesignerHelper */
    protected $designerHelper;

    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository,
        DesignerHelper $designerHelper
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->designerHelper = $designerHelper;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $file = $this->getRequest()->getFiles()->get('artwork');

        if ($file['size'] === 0) {
            $this->messageManager->addErrorMessage(__('Please upload file.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        // toDo process image file (update artwork)

        /** @var Item $orderItem */
        $orderItem = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));

        if ($this->getRequest()->getParam('comment')) {
            $this->processComment($orderItem);
        }

        $this->_eventManager->dispatch('labelin_artwork_designer_upload', [
            'order_item' => $orderItem,
            'comment'    => $this->getRequest()->getParam('comment'),
        ]);

        $this->messageManager->addSuccessMessage(__('Artwork was successfully updated.'));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    protected function processComment(Item $item): self
    {
        $comment = $this->getRequest()->getParam('comment');
        $order = $item->getOrder();
        $authUser = $this->designerHelper->getCurrentAuthUser();

        if (!$order) {
            return $this;
        }

        $order->addStatusToHistory($order->getStatus(), sprintf(
            '%s (%s): %s',
            $authUser ? $authUser->getName() : '',
            $authUser ? $authUser->getRole()->getRoleName() : '',
            $comment
        ));

        $this->orderRepository->save($order);

        return $this;
    }
}
