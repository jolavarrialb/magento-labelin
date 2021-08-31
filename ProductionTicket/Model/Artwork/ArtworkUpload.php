<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Artwork;

use Exception;
use Labelin\ProductionTicket\Helper\ProductionTicketArtworkPdfToProgrammer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Labelin\ProductionTicket\Model\Order\Item;

class ArtworkUpload extends Uploader
{
    /** @var Filesystem */
    protected $fileSystem;

    protected $_allowedExtensions = ['pdf'];

    /** @var ProductionTicketArtworkPdfToProgrammer */
    protected $artworkHelper;

    public function __construct(
        $fileId,
        Mime $fileMime,
        DirectoryList $directoryList,
        ProductionTicketArtworkPdfToProgrammer $artworkHelper
    ) {
        parent::__construct(
            $fileId,
            $fileMime,
            $directoryList
        );

        $this->artworkHelper = $artworkHelper;
    }

    /**
     * @throws Exception
     */
    public function saveArtworkPdf(Item $item): array
    {
        $filename = $this->getFileName($item);
        $destinationFolder = $this->getDestinationFolder($item);
        $result = $this->save($destinationFolder, $filename);
        $result['link'] = $this->artworkHelper->createLinkForDownloadPdf($item, $result);

        return $result;
    }

    protected function getFileName(Item $item): string
    {
        return $this->artworkHelper->getFileName($item, $this->getUploadedFileName());
    }

    protected function getDestinationFolder(Item $item): string
    {
        return $this->artworkHelper->getDestinationFolder($item);
    }
}
