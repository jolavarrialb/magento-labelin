<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\ProductionTicket\Model\Order\Pdf\Item as ItemPdf;
use Labelin\Sales\Helper\ArtworkPreview;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Zend_Pdf_Exception;

class ProductionTicket extends AbstractHelper
{
    const DESTINATION_FOLDER_IMAGE = '%sproduction_ticket/item/images/%s';
    const DESTINATION_FOLDER_PDF = '%sproduction_ticket/item/pdf/%s';

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

    /** @var FileFactory */
    protected $fileFactory;

    public function __construct(
        Context $context,
        Filesystem $fileSystem,
        DirectoryList $directoryList,
        ArtworkPreview $artworkPreviewHelper,
        ItemPdf $itemPdf,
        FileFactory $fileFactory,
        File $filesystemIo
    ) {
        parent::__construct($context);

        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        $this->artworkPreviewHelper = $artworkPreviewHelper;
        $this->filesystemIo = $filesystemIo;
        $this->itemPdf = $itemPdf;
        $this->fileFactory = $fileFactory;
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

    public function getFileName($item, $isPdf = false): string
    {
        $orderId = $item->getOrder()->getIncrementId() ? $item->getOrder()->getIncrementId() : 'Order_ID_' . $item->getOrder()->getId();
        $fileName = $isPdf ? '.pdf' : $this->artworkPreviewHelper->getArtworkFileNameByItem($item);

        return sprintf('%s_%s', $orderId, $fileName);
    }

    /**
     * @param Item $item
     * @return bool
     * @throws FileSystemException
     */
    public function createInProductionTicketImageFile(Item $item): bool
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
     * @return bool
     * @throws FileSystemException
     * @throws Zend_Pdf_Exception
     */
    public function createInProductionTicketPdfFile(Item $item): bool
    {
        $result = false;
        $pdfParams = [
            'item' => $item,
        ];

        $filename = $this->getProductionTicketDestinationPdf($item);
        $destinationFolder = $this->getProductionTicketDestinationPdf();
        $resultPdf = $this->itemPdf->getPdf($pdfParams);
        try {
            if ($this->filesystemIo->checkAndCreateFolder($destinationFolder)) {
                $result = (bool) $this->filesystemIo->write($filename, $resultPdf->render());
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
     * @param $item
     * @return string
     * @throws FileSystemException
     */
    public function getProductionTicketDestinationPdf($item = null)
    {
        $media = $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
        $pdfName = null === $item ? '' : $this->getFileName($item, true);

        return sprintf(static::DESTINATION_FOLDER_PDF, $media->getAbsolutePath(), $pdfName);
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
}
