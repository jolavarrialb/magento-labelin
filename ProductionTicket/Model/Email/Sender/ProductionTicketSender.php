<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Email\Sender;

use Labelin\ProductionTicket\Helper\Programmer as ProgrammerHelper;
use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\Sales\Model\Order;
use Laminas\Mime\Mime;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order as MagentoOrder;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Psr\Log\LoggerInterface;

class ProductionTicketSender extends Sender
{
    /** @var ProductionTicket */
    protected $productionTicket;

    /** @var ProgrammerHelper */
    protected $programmerHelper;

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        MagentoOrder\Email\SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        ProgrammerHelper $programmerHelper
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);

        $this->programmerHelper = $programmerHelper;
    }

    public function send(ProductionTicket $productionTicket): void
    {
        if ($this->programmerHelper->getProgrammerCollection()->getSize() === 0) {
            return;
        }

        /** @var Order */
        $order = $productionTicket->getOrder();

        $transport = [
            'production_ticket' => $productionTicket,
            'order' => $order,
            'attachments' => [
                'image' => [
                    'content' => '',
                    'filename' => '',
                    'type' => Mime::TYPE_OCTETSTREAM
                ],
                'pdf' => [
                    'content' => '',
                    'filename' => '',
                    'type' => 'application/pdf'
                ]
            ]
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($order);
    }

    protected function prepareTemplate(MagentoOrder $order): void
    {
        parent::prepareTemplate($order);

        $programmersCollection = $this->programmerHelper->getProgrammerCollection();

        if ($programmersCollection->getSize() > 0) {
            $this->identityContainer->setCustomerEmail($programmersCollection->getFirstItem()->getData('email'));
            $this->identityContainer->setCustomerName($programmersCollection->getFirstItem()->getData('username'));
        }
    }
}
