<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Exception\FileSystemException;
use Zend_Pdf_Exception;

class ProductionTicketPdf extends ProductionTicketAbstract
{
    protected const DESTINATION_FOLDER_PDF = 'production_ticket/item/pdf/';

    protected const ATTACHED_TYPE_PDF = 'application/pdf';

    /**
     * @param Item $item
     * @return bool
     * @throws FileSystemException
     * @throws Zend_Pdf_Exception
     */
    public function createInProductionTicketAttachment(Item $item): bool
    {
        $result = false;
        $pdfParams = [
            'item' => $item,
        ];

        $filename = $this->getTicketDestinationPdf($item);
        $destinationFolder = $this->getTicketDestinationPdf();
        $resultPdf = $this->itemPdf->getPdf($pdfParams);

        try {
            if (!$resultPdf) {
                $message = sprintf('Incorrect params $pdfParams in ProductionTicketPdf for OrderItemId = %s', $item->getId());
                throw new \Exception($message);
            }

            if ($this->filesystemIo->checkAndCreateFolder($destinationFolder)) {
                $result = (bool)$this->filesystemIo->write($filename, $resultPdf->render());
            }

            if (!$result) {
                $message = sprintf('Pdf File isn`t created for OrderItemId = %s', $item->getId());
                throw new \Exception($message);
            }

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
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
        $pdfName = null === $item ? '' : $this->getFileName($item);

        return sprintf('%s%s%s', $this->getMedia()->getAbsolutePath(), static::DESTINATION_FOLDER_PDF, $pdfName);
    }

    public function getFileName($item): string
    {
        $orderId = $item->getOrder()->getIncrementId() ? $item->getOrder()->getIncrementId() : 'Order_ID_' . $item->getOrder()->getId();
        $fileName = sprintf('%s.pdf', $item->getId());

        return sprintf('%s_%s', $orderId, $fileName);
    }


    /**
     * @param Item $item
     * @return string[]
     * @throws FileSystemException
     */
    public function getEmailAttachment(Item $item): array
    {
        $result = static::ATTACH_FILE_DEFAULT_PARAMS;

        if ($this->checkAttachFileExist($this->getTicketDestinationPdf($item))) {
            $result['content'] = $this->getTicketDestinationPdf($item);
            $result['filename'] = $this->getFileName($item);
            $result['type'] = static::ATTACHED_TYPE_PDF;
        }

        return $result;
    }
}
