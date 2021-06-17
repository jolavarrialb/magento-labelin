<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Email\Sender;

use Labelin\ProductionTicket\Helper\ProductionTicketImage;
use Labelin\ProductionTicket\Helper\ProductionTicketPdf;
use Labelin\ProductionTicket\Helper\Programmer as ProgrammerHelper;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\Sales\Helper\Product\Premade;
use Labelin\Sales\Model\Order;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Sales\Model\Order as MagentoOrder;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Item;
use Psr\Log\LoggerInterface;

class ProductionTicketSender extends Sender
{
    /** @var ProductionTicket */
    protected $productionTicket;

    /** @var ProgrammerHelper */
    protected $programmerHelper;

    /** @var ProductionTicketPdf */
    protected $ticketPdfHelper;

    /** @var ProductionTicketImage */
    protected $ticketImageHelper;

    /** @var Premade */
    protected $premadeHelper;

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        MagentoOrder\Email\SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        ProgrammerHelper $programmerHelper,
        ProductionTicketPdf $ticketPdfHelper,
        ProductionTicketImage $ticketImageHelper,
        Premade $premadeHelper
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);

        $this->programmerHelper = $programmerHelper;
        $this->ticketPdfHelper = $ticketPdfHelper;
        $this->ticketImageHelper = $ticketImageHelper;
        $this->premadeHelper = $premadeHelper;
    }

    /**
     * @param ProductionTicket $productionTicket
     * @throws FileSystemException
     */
    public function send(ProductionTicket $productionTicket): void
    {
        if ($this->programmerHelper->getProgrammerCollection()->getSize() === 0) {
            return;
        }

        /** @var Order */
        $order = $productionTicket->getOrder();
        $orderItem = $productionTicket->getOrderItem();

        $transport = [
            'production_ticket' => $productionTicket,
            'order' => $order,
            'order_number' => $productionTicket->getData('order_item_label'),
            'attachments' => $this->getAttachments($orderItem),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($order);
    }

    /**
     * @param MagentoOrder $order
     */
    protected function prepareTemplate(MagentoOrder $order): void
    {
        parent::prepareTemplate($order);

        $programmersCollection = $this->programmerHelper->getProgrammerCollection();

        if ($programmersCollection->getSize() > 0) {
            $this->identityContainer->setCustomerEmail($programmersCollection->getFirstItem()->getData('email'));
            $this->identityContainer->setCustomerName($programmersCollection->getFirstItem()->getData('username'));
        }
    }

    /**
     * @param Item $item
     * @return array
     */
    protected function getAttachments(Item $item): array
    {
        if ($this->premadeHelper->isPremade($item)) {
            return $this->getPremadeProductAttachments($item);
        }

        return $this->getLabelProductAttachments($item);
    }

    /**
     * @param Item $item
     * @return array
     * @throws FileSystemException
     */
    protected function getPremadeProductAttachments(Item $item): array
    {
        return [
            'pdf' => $this->ticketPdfHelper->getEmailAttachment($item),
        ];

    }

    /**
     * @param Item $item
     * @return array
     * @throws FileSystemException
     */
    protected function getLabelProductAttachments(Item $item): array
    {
        return [
            'image' => $this->ticketImageHelper->getEmailAttachment($item),
            'pdf' => $this->ticketPdfHelper->getEmailAttachment($item),
        ];
    }
}
