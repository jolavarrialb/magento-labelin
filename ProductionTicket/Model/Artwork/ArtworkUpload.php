<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Artwork;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Labelin\ProductionTicket\Model\Order\Item;

class ArtworkUpload extends Uploader
{
    protected const ARTWORK_DESTINATION = '/production_ticket/item/artworkPdf';
    protected const ARTWORK_EXTENSION_PDF = '.pdf';

    /** @var Filesystem */
    protected $fileSystem;

    /** @var DirectoryList */
    protected $directoryList;

    protected $_allowedExtensions = ['pdf'];

    public function __construct(
        $fileId,
        Mime $fileMime,
        DirectoryList $directoryList,
        Filesystem $fileSystem
    ) {
        parent::__construct(
            $fileId,
            $fileMime,
            $directoryList
        );

        $this->fileSystem = $fileSystem;
        $this->directoryList = $directoryList;
    }

    /**
     * @throws Exception
     */
    public function saveArtworkPdf(Item $item):array
    {
        $filename = $this->getFileName($item);
        $destinationFolder = $this->getDestinationFolder($item);

        return $this->save($destinationFolder, $filename);
    }

    protected function getFileName($item): string
    {
        $orderId = $item->getOrder()->getIncrementId() ?: 'Order_ID_' . $item->getOrder()->getId();

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $this->getUploadedFileName());
    }

    protected function getDestinationFolder(Item $item):string
    {
        $folderPath = sprintf('/orderId_%s/itemId_%s/', $item->getOrderId(), $item->getId());

        return sprintf(
            '%s%s%s',
            $this->directoryList->getPath(DirectoryList::MEDIA),
            static::ARTWORK_DESTINATION,
            $folderPath
        );
    }
}
