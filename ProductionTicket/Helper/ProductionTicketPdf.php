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
use Zend_Pdf_Exception;

class ProductionTicketPdf extends AbstractHelper
{
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
     * @param Item $item
     * @return bool
     * @throws FileSystemException
     * @throws Zend_Pdf_Exception
     */
    public function createInProductionTicketPdf(Item $item): bool
    {
        $result = false;
        $pdfParams = [
            'item' => $item,
        ];

        $filename = $this->getTicketDestinationPdf($item);
        $destinationFolder = $this->getTicketDestinationPdf();
        $resultPdf = $this->itemPdf->getPdf($pdfParams);

        try {
            if ($this->filesystemIo->checkAndCreateFolder($destinationFolder)) {
                $result = (bool)$this->filesystemIo->write($filename, $resultPdf->render());
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
    public function getTicketDestinationPdf($item = null)
    {
        $media = $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
        $pdfName = null === $item ? '' : $this->getFileName($item);

        return sprintf(static::DESTINATION_FOLDER_PDF, $media->getAbsolutePath(), $pdfName);
    }

    public function getFileName($item): string
    {
        $orderId =
            $item->getOrder()->getIncrementId() ?
                $item->getOrder()->getIncrementId() :
                'Order_ID_' . $item->getOrder()->getId();

        $fileName = sprintf('%s.pdf', $item->getId());

        return sprintf('%s_%s', $orderId, $fileName);
    }
}
