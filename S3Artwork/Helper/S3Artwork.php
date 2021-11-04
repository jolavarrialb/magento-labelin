<?php

declare(strict_types=1);

namespace Labelin\S3Artwork\Helper;

use Exception;
use Labelin\ProductionTicket\Helper\ProductionTicketArtworkPdfToProgrammer;
use Labelin\Sales\Helper\ArtworkPreview;
use Labelin\ProductionTicket\Model\Order\Item;
use Laminas\Mime\Mime;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class S3Artwork extends AbstractHelper
{
    protected const S3_ARTWORK_OPTIONS_PATH = 'labelin_s3_artwork/options/save_path';

    /** @var Filesystem */
    protected $fileSystem;

    /** @var DirectoryList */
    protected $directoryList;

    /** @var File */
    protected $filesystemIo;

    /** @var LoggerInterface */
    protected $logger;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var ArtworkPreview */
    protected $artworkPreviewHelper;

    /** @var ProductionTicketArtworkPdfToProgrammer */
    protected $artworkPdfToProgrammerHelper;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Filesystem $fileSystem,
        DirectoryList $directoryList,
        StoreManagerInterface $storeManager,
        ArtworkPreview $artworkPreviewHelper,
        ProductionTicketArtworkPdfToProgrammer $artworkPdfToProgrammerHelper,
        File $filesystemIo
    ) {
        parent::__construct($context);

        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        $this->filesystemIo = $filesystemIo;
        $this->storeManager = $storeManager;
        $this->artworkPdfToProgrammerHelper = $artworkPdfToProgrammerHelper;
        $this->artworkPreviewHelper = $artworkPreviewHelper;
        $this->logger = $logger;
    }

    /**
     * @param Item $item
     * @return bool
     * @throws Exception
     */
    public function saveArtworkToS3Folder(Item $item): bool
    {
        $destination = preg_replace('~[\/]{2,}~', '/', $this->getPath($item));
        $sourceImage = $this->getArtworkPath($item);
        $resultImage = preg_replace('~[\/]{2,}~', '/', $this->getS3ArtworkPath($item));

        try {
            $result = false;

            if ($this->filesystemIo->checkAndCreateFolder($destination)) {
                $result = (bool)$this->filesystemIo->write($resultImage, $sourceImage);
            }

            if (!$result) {
                $message = sprintf('Artwork S3 File isn`t created for OrderItemId = %s', $item->getId());
                throw new LocalizedException(__($message));
            }
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function getPath(Item $item): string
    {
        return sprintf(
            '%s%s%s',
            $this->getPubMedia()->getAbsolutePath(),
            $this->getS3Path(),
            $this->getOrderItemFolderPath($item)
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getS3Path(): string
    {
        $configValue = $this->scopeConfig->getValue(
            static::S3_ARTWORK_OPTIONS_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );

        return DIRECTORY_SEPARATOR . $configValue . DIRECTORY_SEPARATOR;
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function getS3ArtworkPath(Item $item): string
    {
        return sprintf('%s%s', $this->getPath($item), $this->getFileName($item));
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getArtworkPath(Item $item): string
    {
        if ($item->getArtworkToProduction()) {
            return $this->artworkPdfToProgrammerHelper->getProductionFileDestination($item);
        }

        $originalImagePath = $this->artworkPreviewHelper->getArtworkOptionsPathByItem($item);

        return sprintf('%s%s', $this->getPubMedia()->getAbsolutePath(), $originalImagePath);
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getArtworkOptionsPathByItem(Item $item): string
    {
        $imageFilePath = $this->getS3ArtworkPath($item);
        $fileExist = $this->filesystemIo->fileExists($imageFilePath);

        return $fileExist ? $imageFilePath : '';
    }

    public function getFileName(Item $item): string
    {
        if ($item->getArtworkToProduction()) {
            return $this->artworkPdfToProgrammerHelper->getFileName($item);
        }

        $orderId = $item->getOrder()->getIncrementId() ?: 'Order_ID_' . $item->getOrder()->getId();
        $fileName = $this->artworkPreviewHelper->getArtworkFileNameByItem($item);

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $fileName);
    }

    /**
     * @return WriteInterface
     * @throws FileSystemException
     */
    public function getPubMedia(): WriteInterface
    {
        return $this->fileSystem->getDirectoryWrite($this->directoryList::MEDIA);
    }

    /**
     * @param Item|null $item
     * @return string
     */
    protected function getOrderItemFolderPath(Item $item): string
    {
        return sprintf('/orderId_%s/itemId_%s/', $item->getOrderId(), $item->getId());
    }

    /**
     * @param $item
     * @return array
     */
    public function getArtworkAttachment($item): array
    {
        return [
            'content' => $this->getArtworkContent($item),
            'filename' => $this->getFileName($item),
            'type' => Mime::TYPE_OCTETSTREAM,
        ];
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    protected function getArtworkContent(Item $item): string
    {
        return sprintf(
            '%s%s',
            preg_replace('~[\/]{2,}~', '/', $this->getPath($item)),
            $this->getFileName($item)
        );
    }
}
