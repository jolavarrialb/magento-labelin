<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\Sales\Model\Order\Item;
use Laminas\Mime\Mime;
use Magento\Framework\Exception\FileSystemException;

class ProductionTicketImage extends ProductionTicketAbstract
{
    protected const DESTINATION_FOLDER_IMAGE = 'production_ticket/item/images/';

    /**
     * @param Item $item
     * @return bool
     * @throws FileSystemException
     */
    public function createInProductionTicketAttachment(Item $item): bool
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
                $message = sprintf('Artwork Image File isn`t created for OrderItemId = %s', $item->getId());
                throw new \Exception($message);
            }

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }

    /**
     * @param null|Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getProductionTicketDestination($item = null): string
    {
        $imageName = null === $item ? '' : $this->getFileName($item);

        return sprintf('%s%s%s', $this->getMedia()->getAbsolutePath(), static::DESTINATION_FOLDER_IMAGE, $imageName);
    }

    /**
     * @param Item $item
     * @return string
     * @throws FileSystemException
     */
    public function getProductionTicketSourceImagePath(Item $item): string
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
        $imageFilePath = $this->getProductionTicketDestination($item);
        $fileExist = $this->filesystemIo->fileExists($imageFilePath);

        return $fileExist ? $imageFilePath : '';
    }

    public function getFileName(Item $item): string
    {
        $orderId = $item->getOrder()->getIncrementId() ? $item->getOrder()->getIncrementId() : 'Order_ID_' . $item->getOrder()->getId();
        $fileName = $this->artworkPreviewHelper->getArtworkFileNameByItem($item);

        return sprintf('%s_%s_%s', $orderId, $item->getId(), $fileName);
    }

    /**
     * @param Item $item
     * @return string[]
     * @throws FileSystemException
     */
    public function getEmailAttachment(Item $item): array
    {
        $result = static::ATTACH_FILE_DEFAULT_PARAMS;

        if ($this->checkAttachFileExist($this->getProductionTicketDestination($item))) {
            $result['content'] = $this->getProductionTicketDestination($item);
            $result['filename'] = $this->getFileName($item);
            $result['type'] = Mime::TYPE_OCTETSTREAM;
        }

        return $result;
    }
}
