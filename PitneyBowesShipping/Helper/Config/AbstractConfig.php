<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper\Config;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

abstract class AbstractConfig extends AbstractHelper
{
    /** @var EncryptorInterface */
    protected $encryptor;

    /** @var Config */
    protected $config;

    /** @var array */
    protected $xmlPathSettings = [];

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor,
        Config $config,
        array $xmlPathSettings = []
    ) {
        parent::__construct($context);

        $this->encryptor = $encryptor;
        $this->config = $config;
        $this->xmlPathSettings = $xmlPathSettings;
    }

    public function getApiKey(): string
    {
        $value = (string)$this->scopeConfig->getValue($this->xmlPathSettings['api_key']);

        return $this->encryptor->decrypt($value);
    }

    public function getApiSecret(): string
    {
        $value = (string)$this->scopeConfig->getValue($this->xmlPathSettings['api_secret']);

        return $this->encryptor->decrypt($value);
    }

    public function getApiUrl(): string
    {
        return (string)$this->scopeConfig->getValue($this->xmlPathSettings['api_url']);
    }

    public function getApiAccessToken(): string
    {
        return (string)$this->scopeConfig->getValue($this->xmlPathSettings['api_token']);
    }

    public function saveApiAccessToken(string $token): self
    {
        $this->config->saveConfig($this->xmlPathSettings['api_token'], $token);

        return $this;
    }
}
