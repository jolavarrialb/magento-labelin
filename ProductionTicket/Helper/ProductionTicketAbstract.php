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
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\Io\File;
use Psr\Log\LoggerInterface;

abstract class ProductionTicketAbstract extends AbstractHelper
{
    protected const ATTACH_FILE_DEFAULT_PARAMS = [
        'content' => '',
        'filename' => '',
        'type' => '',
    ];

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

    /** @var LoggerInterface  */
    protected $logger;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
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
        $this->logger = $logger;
    }

    /**
     * @return WriteInterface
     * @throws FileSystemException
     */
    protected function getMedia(): WriteInterface
    {
        return $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
    }

    abstract public function createInProductionTicketAttachment(Item $item): bool;

    abstract public function getEmailAttachment(Item $item): array;

    public function checkAttachFileExist(string $filepath): bool
    {
        return $this->filesystemIo->fileExists($filepath);
    }
}
