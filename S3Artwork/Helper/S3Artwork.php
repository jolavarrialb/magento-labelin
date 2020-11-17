<?php

declare(strict_types=1);

namespace Labelin\S3Artwork\Helper;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Tests\NamingConvention\true\bool;

class S3Artwork extends AbstractHelper
{
    /** @var Filesystem */
    protected $fileSystem;

    /** @var DirectoryList */
    protected $directoryList;

    /** @var File */
    protected $filesystemIo;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Filesystem $fileSystem,
        DirectoryList $directoryList,
        File $filesystemIo
    ) {
        parent::__construct($context);

        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        $this->filesystemIo = $filesystemIo;
        $this->logger = $logger;
    }

    public function saveArtworkToS3Folder(Item $item): bool
    {
        $destination = $this->getS3Destination();
        $sourceImage = $this->getArtworkPath($item);
        $resultImage = $this->getS3Destination($item);

        try {
            $result = false;

            if ($this->filesystemIo->checkAndCreateFolder($destination)) {
                $result = $this->filesystemIo->cp($sourceImage, $resultImage);
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
     * @param null|Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getS3Destination($item = null): string
    {
        $imageName = null === $item ? '' : $this->getFileName($item);

        return sprintf('%s%s%s', $this->getMedia()->getAbsolutePath(), static::DESTINATION_FOLDER_IMAGE, $imageName);
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getArtworkPath(Item $item): string
    {
        $originalImagePath = $this->artworkPreviewHelper->getArtworkOptionsPathByItem($item);

        return sprintf('%s%s', $this->getMedia()->getAbsolutePath(), $originalImagePath);
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getArtworkOptionsPathByItem(Item $item): string
    {
        $imageFilePath = $this->getS3Destination($item);
        $fileExist = $this->filesystemIo->fileExists($imageFilePath);

        return $fileExist ? $imageFilePath : '';
    }

    public function getFileName(Item $item): string
    {
        $orderId = $item->getOrder()->getIncrementId() ?: 'Order_ID_' . $item->getOrder()->getId();
        $fileName = $this->artworkPreviewHelper->getArtworkFileNameByItem($item);

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $fileName);
    }

}
