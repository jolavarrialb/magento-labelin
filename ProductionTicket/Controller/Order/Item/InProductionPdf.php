<?php
/** TODO DELETE THIS FILE */
declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Order\Item;

use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Labelin\ProductionTicket\Model\Order\Pdf\Item as ItemPdf;

class InProductionPdf extends Action
{
    /** @var OrderItemRepositoryInterface */
    protected $orderItemRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ItemPdf  */
    protected $itemPdf;

    /** @var FileFactory  */
    protected $fileFactory;


    public function __construct(
        Context $context,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository,
        ItemPdf $itemPdf,
        FileFactory $fileFactory
    ) {
        parent::__construct($context);

        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->itemPdf = $itemPdf;
        $this->fileFactory = $fileFactory;

    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('item_id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $item = $this->orderItemRepository->get($this->getRequest()->getParam('item_id'));


        $this->_eventManager->dispatch('labelin_order_item_production_status_after_test', ['item' => $item]);
    }
}
