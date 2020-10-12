<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\ProductionTicket\Model\Order\Pdf\Item as ItemPdf;
use Labelin\Sales\Helper\ArtworkPreview;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;

class ProductionTicketImage extends AbstractHelper
{
    const DESTINATION_FOLDER_IMAGE = '%sproduction_ticket/item/images/%s';

    /** @var Filesystem */
    protected $fileSystem;

    /** @var DirectoryList */
    protected $directoryList;

    /** @var ArtworkPreview */
    protected $artworkPreviewHelper;

    /** @var File */
    protected $filesystemIo;

    /** @var ItemPdf */
    protected $itemPdf;

    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        DirectoryList $directoryList,
        ArtworkPreview $artworkPreviewHelper,
        ItemPdf $itemPdf,
        File $filesystemIo
    ) {
        parent::__construct($context);

        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        $this->artworkPreviewHelper = $artworkPreviewHelper;
        $this->filesystemIo = $filesystemIo;
        $this->itemPdf = $itemPdf;
    }

    /**
     * @param null|Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getProductionTicketDestination($item = null)
    {
        $media = $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
        $imageName = null === $item ? '' : $this->getFileName($item);

        return sprintf(static::DESTINATION_FOLDER_IMAGE, $media->getAbsolutePath(), $imageName);
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getProductionTicketSourceImagePath(Item $item)
    {
        $media = $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
        $originalImagePath = $this->artworkPreviewHelper->getArtworkOptionsPathByItem($item);

        return sprintf('%s%s', $media->getAbsolutePath(), $originalImagePath);
    }

    /**
     * @param Item $item
     * @return bool
     * @throws FileSystemException
     */
    public function createInProductionTicketImage(Item $item): bool
    {
        $destinationFolder = $this->getProductionTicketDestination();
        $sourceImage = $this->getProductionTicketSourceImagePath($item);
        $resultImage = $this->getProductionTicketDestination($item);

        try {
            $result = false;

            if ($this->filesystemIo->checkAndCreateFolder($destinationFolder)) {
                $result = $this->filesystemIo->cp($sourceImage, $resultImage);
            }

            if (!$result) {
                throw new \Exception('File not create');
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getArtworkOptionsPathByItem(Item $item): string
    {
        $imageFilePath = $this->getProductionTicketDestination($item);
        $fileExist = $this->filesystemIo->fileExists($imageFilePath);

        return $fileExist ? $imageFilePath : '';
    }

    public function getFileName(Item $item): string
    {
        $orderId =
            $item->getOrder()->getIncrementId() ?
                $item->getOrder()->getIncrementId() :
                'Order_ID_' . $item->getOrder()->getId();

        $fileName = $this->artworkPreviewHelper->getArtworkFileNameByItem($item);

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $fileName);
    }
}
