<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Email;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\Template\TransportBuilderByStore;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\SenderBuilder as MagentoSenderBuilder;

class SenderBuilder extends MagentoSenderBuilder
{
    /** @var FileDriver */
    protected $fileDriver;

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        TransportBuilder $transportBuilder,
        FileDriver $fileDriver,
        TransportBuilderByStore $transportBuilderByStore = null
    ) {
        parent::__construct($templateContainer, $identityContainer, $transportBuilder, $transportBuilderByStore);

        $this->fileDriver = $fileDriver;
    }

    /**
     * @throws FileSystemException
     */
    public function send(): void
    {
        $templateVars = $this->templateContainer->getTemplateVars();

        if (!array_key_exists('attachments', $templateVars) || !$templateVars['attachments']) {
            parent::send();

            return;
        }

        foreach ($templateVars['attachments'] as $attach) {
            if (!$attach['content']) {
                continue;
            }

            $this->transportBuilder->addAttachment(
                $this->fileDriver->fileGetContents($attach['content']),
                $attach['filename'],
                $attach['type']
            );
        }

        parent::send();
    }
}
