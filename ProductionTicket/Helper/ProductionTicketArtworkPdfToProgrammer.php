<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Serialize\Serializer\Serialize;

class ProductionTicketArtworkPdfToProgrammer extends AbstractHelper
{
    protected const ARTWORK_FILE_DESTINATION = '/production_ticket/item/artworkPdf';

    protected const ARTWORK_VIEW_CONTROLLER_PATH = 'production_ticket/order_item/artworkDownload';

    /** @var DirectoryList */
    protected $directoryList;

    /** @var Serialize  */
    protected $json;

    public function __construct(
        DirectoryList $directoryList,
        Serialize $json
    ) {
        $this->directoryList = $directoryList;
        $this->json = $json;
    }

    public function createLinkForDownloadPdf(Item $item, array $designerArtworkInfo): string
    {
        $fileName = $designerArtworkInfo['name'];
        $href = $this->_urlBuilder->getUrl(static::ARTWORK_VIEW_CONTROLLER_PATH, ['id' => $item->getId()]);

        return sprintf('<a href="%s" target="_blank">%s</a>', $href, $fileName);
    }

    public function getFileName(Item $item, $filename = ''): string
    {
        $orderId = $item->getOrder()->getIncrementId() ?: 'Order_ID_' . $item->getOrder()->getId();

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $filename);
    }

    public function getDestinationFolder(Item $item): string
    {
        $folderPath = sprintf('/orderId_%s/itemId_%s/', $item->getOrderId(), $item->getId());

        return sprintf(
            '%s%s%s',
            $this->directoryList->getPath(DirectoryList::MEDIA),
            static::ARTWORK_FILE_DESTINATION,
            $folderPath
        );
    }
}
