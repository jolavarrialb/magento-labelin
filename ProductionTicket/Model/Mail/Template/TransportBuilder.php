<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Mail\Template;

use Magento\Framework\App\TemplateTypesInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\AddressConverter;
use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\MimeInterface;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Magento\Framework\Mail\MimePartInterfaceFactory;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder as MagentoTransportBuilder;
use Magento\Framework\Mail\TemplateInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Phrase;
use Laminas\Mime\PartFactory;
use Laminas\Mime\Mime;

class TransportBuilder extends MagentoTransportBuilder
{
    protected const PDF_TYPE = 'application/pdf';

    /** @var string */
    protected $templateIdentifier;

    /** @var string */
    protected $templateModel;

    /** @var array */
    protected $templateVars;

    /** @var array */
    protected $templateOptions;

    /** @var TransportInterface */
    protected $transport;

    /** @var FactoryInterface */
    protected $templateFactory;

    /** @var ObjectManagerInterface */
    protected $objectManager;

    /** @var MessageInterface */
    protected $message;

    /** @var SenderResolverInterface */
    protected $_senderResolver;

    /** @var TransportInterfaceFactory */
    protected $mailTransportFactory;

    /** @var MimePartInterfaceFactory */
    protected $mimePartInterfaceFactory;

    /** @var MimeMessageInterfaceFactory */
    protected $mimeMessageInterfaceFactory;

    /** @var array */
    protected $messageData = [];

    /** @var EmailMessageInterfaceFactory */
    protected $emailMessageInterfaceFactory;

    /** @var AddressConverter */
    protected $addressConverter;

    /** @var array */
    protected $attachments = [];

    /** @var PartFactory */
    protected $part;

    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        MessageInterfaceFactory $messageFactory = null,
        EmailMessageInterfaceFactory $emailMessageInterfaceFactory = null,
        MimeMessageInterfaceFactory $mimeMessageInterfaceFactory = null,
        MimePartInterfaceFactory $mimePartInterfaceFactory = null,
        AddressConverter $addressConverter = null
    ) {
        $this->templateFactory = $templateFactory;
        $this->objectManager = $objectManager;
        $this->_senderResolver = $senderResolver;
        $this->mailTransportFactory = $mailTransportFactory;

        $this->mimePartInterfaceFactory = $mimePartInterfaceFactory ?:
            $this->objectManager->get(MimePartInterfaceFactory::class);
        $this->emailMessageInterfaceFactory = $emailMessageInterfaceFactory ?:
            $this->objectManager->get(EmailMessageInterfaceFactory::class);
        $this->mimeMessageInterfaceFactory = $mimeMessageInterfaceFactory ?:
            $this->objectManager->get(MimeMessageInterfaceFactory::class);

        $this->addressConverter = $addressConverter ?: $this->objectManager
            ->get(AddressConverter::class);

        $this->part = $objectManager->get(PartFactory::class);

        parent::__construct(
            $templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory,
            $messageFactory,
            $emailMessageInterfaceFactory,
            $mimeMessageInterfaceFactory,
            $mimePartInterfaceFactory,
            $addressConverter
        );
    }

    public function addAttachment($content, string $filename, string $type): self
    {
        if (!$content) {
            return $this;
        }

        $attachmentPart = $this->part->create()
            ->setContent($content)
            ->setFileName($filename)
            ->setType($type)
            ->setDisposition(Mime::DISPOSITION_ATTACHMENT)
            ->setEncoding(Mime::ENCODING_BASE64);

        $this->attachments[] = $attachmentPart;

        return $this;
    }

    /**
     * @param array|string $address
     * @param string       $name
     *
     * @return $this
     */
    public function addCc($address, $name = ''): self
    {
        $this->addAddressByType('cc', $address, $name);

        return $this;
    }

    /**
     * @param array|string $address
     * @param string       $name
     *
     * @return $this
     */
    public function addTo($address, $name = ''): self
    {
        $this->addAddressByType('to', $address, $name);

        return $this;
    }

    /**
     * @param array|string $address
     *
     * @return $this
     */
    public function addBcc($address): self
    {
        $this->addAddressByType('bcc', $address);

        return $this;
    }

    /**
     * @param string      $email
     * @param string|null $name
     *
     * @return $this
     */
    public function setReplyTo($email, $name = null): self
    {
        $this->addAddressByType('replyTo', $email, $name);

        return $this;
    }

    /**
     * @param array|string $from
     *
     * @return $this
     * @throws MailException
     */
    public function setFrom($from): self
    {
        return $this->setFromByScope($from);
    }

    /**
     * @param array|string $from
     * @param mixed        $scopeId
     *
     * @return $this
     * @throws MailException
     */
    public function setFromByScope($from, $scopeId = null): self
    {
        $result = $this->_senderResolver->resolve($from, $scopeId);
        $this->addAddressByType('from', $result['email'], $result['name']);

        return $this;
    }

    /**
     * @param string $templateIdentifier
     *
     * @return $this
     */
    public function setTemplateIdentifier($templateIdentifier): self
    {
        $this->templateIdentifier = $templateIdentifier;

        return $this;
    }

    /**
     * @param string $templateModel
     *
     * @return $this
     */
    public function setTemplateModel($templateModel): self
    {
        $this->templateModel = $templateModel;

        return $this;
    }

    /**
     * @param array $templateVars
     *
     * @return $this
     */
    public function setTemplateVars($templateVars): self
    {
        $this->templateVars = $templateVars;

        return $this;
    }

    /**
     * @param array $templateOptions
     *
     * @return $this
     */
    public function setTemplateOptions($templateOptions): self
    {
        $this->templateOptions = $templateOptions;

        return $this;
    }

    public function getTransport(): TransportInterface
    {
        try {
            $this->prepareMessage();
            $mailTransport = $this->mailTransportFactory->create(['message' => clone $this->message]);
        } finally {
            $this->reset();
        }

        return $mailTransport;
    }

    protected function reset(): self
    {
        $this->messageData = [];
        $this->templateIdentifier = null;
        $this->templateVars = null;
        $this->templateOptions = null;

        return $this;
    }

    protected function getTemplate(): TemplateInterface
    {
        return $this->templateFactory
            ->get($this->templateIdentifier, $this->templateModel)
            ->setVars($this->templateVars)
            ->setOptions($this->templateOptions);
    }

    protected function addAddressByType(string $addressType, $email, ?string $name = null): void
    {
        if (is_string($email)) {
            $this->messageData[$addressType][] = $this->addressConverter->convert($email, $name);

            return;
        }

        $convertedAddressArray = $this->addressConverter->convertMany($email);

        if (isset($this->messageData[$addressType])) {
            $this->messageData[$addressType] = array_merge($this->messageData[$addressType], $convertedAddressArray);
        } else {
            $this->messageData[$addressType] = $convertedAddressArray;
        }
    }

    protected function prepareMessage(): self
    {
        $template = $this->getTemplate();
        $content = $template->processTemplate();

        switch ($template->getType()) {
            case TemplateTypesInterface::TYPE_TEXT:
                $part['type'] = MimeInterface::TYPE_TEXT;
                break;
            case TemplateTypesInterface::TYPE_HTML:
                $part['type'] = MimeInterface::TYPE_HTML;
                break;
            default:
                throw new LocalizedException(new Phrase('Unknown template type'));
        }

        $mimePart = $this->mimePartInterfaceFactory->create(['content' => $content]);

        $parts = count($this->attachments) ? array_merge([$mimePart], $this->attachments) : [$mimePart];

        $this->messageData['encoding'] = $mimePart->getCharset();
        $this->messageData['body'] = $this->mimeMessageInterfaceFactory->create(['parts' => $parts]);

        $this->messageData['subject'] = (string)$template->getSubject();

        $this->message = $this->emailMessageInterfaceFactory->create($this->messageData);

        return $this;
    }
}
