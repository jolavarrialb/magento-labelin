<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order\Item;

use Labelin\Sales\Model\Order\Item;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class ArtworkResetDeclineCount extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $itemRepository;

    public function __construct(
        Action\Context $context,
        OrderItemRepositoryInterface $orderItemRepository
    ) {
        parent::__construct($context);

        $this->itemRepository = $orderItemRepository;
    }

    public function execute(): ResponseInterface
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid Form Key'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $item */
        $item = $this->itemRepository->get($this->getRequest()->getParam('item_id'));

        try {
            $item->resetDeclineArtworkCounter();

            $this->messageManager->addSuccessMessage(__('Artwork was successfully updated.'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
