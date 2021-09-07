<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Serialize\Serializer\Json;

class ProductionTicketArtworkPdfToProgrammer extends AbstractHelper
{
    protected const ARTWORK_FILE_DESTINATION = '/production_ticket/item/artworkPdf';

    protected const ARTWORK_VIEW_CONTROLLER_PATH = 'production_ticket/order_item/artworkDownload';

    /** @var DirectoryList */
    protected $directoryList;

    /** @var Json */
    protected $json;

    /** @var File */
    protected $fileSystemIo;

    public function __construct(
        DirectoryList $directoryList,
        Json $json,
        Context $context,
        File $fileSystemIo
    ) {
        parent::__construct($context);

        $this->directoryList = $directoryList;
        $this->json = $json;
        $this->fileSystemIo = $fileSystemIo;
    }

    public function createLinkForDownloadPdf(Item $item, array $designerArtworkInfo): string
    {
        $fileName = $designerArtworkInfo['name'];
        $href = $this->_urlBuilder->getUrl(
            static::ARTWORK_VIEW_CONTROLLER_PATH,
            [
                'id' => $item->getId(),
                'key' => $designerArtworkInfo['secret_key'],
            ]
        );

        return sprintf('<a href="%s" target="_blank">%s</a>', $href, $fileName);
    }

    public function getFileName(Item $item, $filename = ''): string
    {
        if ($item->getArtworkToProduction()) {
            $filename = $this->getSavedFilename($item);
        }

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

    public function getArtworkLink(Item $item): string
    {
        if ($item->getArtworkToProduction()) {
            $artworkData = $this->json->unserialize($item->getArtworkToProduction());

            return $artworkData['link'] ?? '';
        }

        return '';
    }

    public function getEmailAttachment($item): array
    {
        if ($this->checkAttachFileExist($this->getProductionFileDestination($item))) {
            return [
                'content' => $this->getProductionFileDestination($item),
                'filename' => $this->getFileName($item),
                'type' => $this->getSavedFileType($item),
            ];
        }

        return [];
    }

    public function checkAttachFileExist(string $filepath): bool
    {
        return $this->fileSystemIo->fileExists($filepath);
    }

    public function getSavedFileName(Item $item): string
    {
        $fileData = $this->getItemArtworkProductionFileData($item);

        return $fileData['name'] ?? '';
    }

    public function getProductionFileDestination(Item $item): string
    {
        return sprintf('%s%s', $this->getSavedFilePath($item), $this->getSavedFile($item));
    }

    public function getSavedFilePath(Item $item): string
    {
        $fileData = $this->getItemArtworkProductionFileData($item);

        return $fileData['path'] ?? '';
    }

    public function getSavedFile(Item $item): string
    {
        $fileData = $this->getItemArtworkProductionFileData($item);

        return $fileData['file'] ?? '';
    }

    public function getSavedFileType(Item $item): string
    {
        $fileData = $this->getItemArtworkProductionFileData($item);

        return $fileData['type'] ?? '';
    }

    protected function getItemArtworkProductionFileData(Item $item): array
    {
        return $this->json->unserialize($item->getArtworkToProduction());
    }
}
