<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Order\Item;

use Labelin\ProductionTicket\Helper\ProductionTicketArtworkPdfToProgrammer as ArtworkHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class ArtworkDownload extends Action implements HttpGetActionInterface
{
    /** @var FileFactory */
    protected $fileFactory;

    /** @var ArtworkHelper */
    protected $artworkHelper;

    /** @var OrderItemRepositoryInterface */
    protected $itemRepository;

    /** @var Json */
    protected $serialize;

    public function __construct(
        FileFactory $fileFactory,
        ArtworkHelper $artworkHelper,
        OrderItemRepositoryInterface $itemRepository,
        Json $serialize,
        Context $context
    ) {
        parent::__construct($context);

        $this->fileFactory = $fileFactory;
        $this->artworkHelper = $artworkHelper;
        $this->itemRepository = $itemRepository;
        $this->serialize = $serialize;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('id')) {
            $this->messageManager->addErrorMessage(__('Please specify order item.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $item = $this->itemRepository->get($this->getRequest()->getParam('id'));
        $artworkData = $this->serialize->unserialize($item->getArtworkToProduction());

        $content = [
            'type' => 'filename',
            'value' => $artworkData['path'] . $artworkData['file'],
        ];

        $this->fileFactory->create(
            $artworkData['name'],
            $content,
            DirectoryList::ROOT,
            $artworkData['type']
        );
    }
}
