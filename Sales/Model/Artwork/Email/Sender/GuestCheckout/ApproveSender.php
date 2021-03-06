<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender\GuestCheckout;

use Labelin\ProductionTicket\Helper\ProductionTicketImage as ProductionTicketImageHelper;
use Labelin\Sales\Helper\Designer as DesignerHelper;
use Labelin\Sales\Model\Artwork\Email\Container\Identity;
use Magento\Backend\Model\Url;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\SenderBuilderFactory;
use Psr\Log\LoggerInterface;

class ApproveSender extends AbstractSender
{
    /** @var ProductionTicketImageHelper */
    protected $ticketImageHelper;

    public function __construct(
        Template $templateContainer,
        Identity $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        DesignerHelper $designerHelper,
        Url $urlBuilder,
        ProductionTicketImageHelper $ticketImageHelper
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $designerHelper,
            $urlBuilder
        );

        $this->ticketImageHelper = $ticketImageHelper;
    }

    public function getTemplateVars(): array
    {
        if (!$this->getOrder()) {
            return [];
        }

        return [
            'template_subject' => __(
                'Your proof is ready. Order #%1.',
                $this->getOrder()->getIncrementId(),
            )->render(),
            'customer_name' => $this->getOrder()->getCustomerName(),
            'attachments' => [
                'image' => $this->ticketImageHelper->getEmailAttachment($this->getItem()),
            ],
        ];
    }
}
