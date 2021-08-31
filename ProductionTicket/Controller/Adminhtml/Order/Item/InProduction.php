<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Order\Item;

use Labelin\ProductionTicket\Model\Artwork\ArtworkUploadFactory;
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
    protected const ARTWORK_IMAGE_FILE_NAME = 'artwork_to_programmer';

    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ArtworkUploadFactory */
    protected $artworkUploadFactory;

    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository,
        ArtworkUploadFactory $artworkUploadFactory
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->artworkUploadFactory = $artworkUploadFactory;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid form key'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        if (!$uploader = $this->artworkUploadFactory->create(['fileId' => static::ARTWORK_IMAGE_FILE_NAME])) {
            $this->messageManager->addErrorMessage(__('Artwork for Programmer is not uploaded'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        /** @var Item $item */
        $item = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));
        /** @var Order $order */
        $order = $item->getOrder();

        try {
            $item->setUploadPdfSerializedData($uploader->saveArtworkPdf($item));
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
