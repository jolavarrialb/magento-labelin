<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Sender;

use Labelin\S3Artwork\Helper\S3Artwork;
use Labelin\Sales\Model\Artwork\Email\Container\Identity;
use Labelin\Sales\Model\Order;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\DataObject;
use Magento\Framework\Url;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Email\SenderBuilderFactory;
use Magento\User\Model\User;
use Psr\Log\LoggerInterface;

class UpdateByDesignerSender extends Sender
{
    /** @var Url */
    protected $urlBuilder;

    /** @var User */
    protected $user;

    /** @var S3Artwork */
    protected $s3ArtworkHelper;

    public function __construct(
        Template $templateContainer,
        Identity $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        Url $urlBuilder,
        S3Artwork $s3ArtworkHelper
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);

        $this->urlBuilder = $urlBuilder;
        $this->s3ArtworkHelper = $s3ArtworkHelper;
    }

    public function send(Item $item, string $comment): void
    {
        /** @var Order $order */
        $order = $item->getOrder();

        if (!$order) {
            return;
        }

        $transport = [
            'item' => $item,
            'customer_name' => $order->getCustomerName(),
            'comment' => $comment,
            'order_url' => $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $order->getId()]),
            'attachments' => [
                'img' => $this->s3ArtworkHelper->getArtworkAttachment($item),
            ],
        ];

        $transportObject = new DataObject($transport);
        $this->templateContainer->setTemplateVars($transportObject->getData());

        $this->checkAndSend($order);
    }
}
