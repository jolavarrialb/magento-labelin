<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Email\Sender;

use Labelin\Sales\Helper\Shipper;
use Labelin\Sales\Model\Order;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Email\SenderBuilderFactory;
use Psr\Log\LoggerInterface;

class ShipperOrderSender extends Sender
{
    /**
     * @var Shipper
     */
    protected $shipperHelper;

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        Shipper $shipperHelper
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer
        );

        $this->shipperHelper = $shipperHelper;
    }

    public function send(Order $order): void
    {
        if ($this->shipperHelper->getShippersCollection()->getSize() === 0) {
            return;
        }

        $transport = [
            'order_id' => $order->getIncrementId(),
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($order);
    }
}
