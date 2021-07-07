<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper\Config;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Parameter;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\SpecialService;
use Labelin\PitneyBowesShipping\Helper\GeneralConfig;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

abstract class AbstractConfig extends AbstractHelper
{
    protected const PKG_SERVICES = 'packagesServices';
    protected const PKG_TYPES = 'packagesTypes';

    /** @var EncryptorInterface */
    protected $encryptor;

    /** @var Config */
    protected $config;

    /** @var array */
    protected $xmlPathSettings;

    /** @var GeneralConfig */
    protected $generalConfig;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor,
        Config $config,
        GeneralConfig $generalConfig,
        array $xmlPathSettings = []
    ) {
        parent::__construct($context);

        $this->encryptor = $encryptor;
        $this->config = $config;
        $this->xmlPathSettings = $xmlPathSettings;
        $this->generalConfig = $generalConfig;
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

    public function getAllowedMethods(): array
    {
        return explode(',', $this->scopeConfig->getValue($this->xmlPathSettings['allowed_methods']));
    }

    public function getContainer(): string
    {
        return (string)$this->scopeConfig->getValue($this->xmlPathSettings['container']);
    }

    public function getMerchantId(): string
    {
        return (string)$this->scopeConfig->getValue($this->xmlPathSettings['merchant_id']);
    }

    protected function getPkgServicesConfig(): array
    {
        return $this->generalConfig->getCode(static::PKG_SERVICES);
    }

    public function getParcelType(string $packageContainer = ''): ?string
    {
        if (!$packageContainer) {
            return null;
        }

        $packagesServices = $this->getPkgServicesConfig();

        return array_key_exists($packageContainer, $packagesServices) ? $packagesServices[$packageContainer]['parcelType'] : '';
    }

    public function getServiceId(string $packageContainer = ''): ?string
    {
        if (!$packageContainer) {
            return null;
        }

        $packagesServices = $this->getPkgServicesConfig();

        return array_key_exists($packageContainer, $packagesServices) ? $packagesServices[$packageContainer]['serviceId'] : null;
    }

    protected function getPkgTypes(): array
    {
        return $this->generalConfig->getCode(static::PKG_TYPES);
    }

    public function getSpecialServices(string $packageContainer = ''): ?array
    {
        if (!$packageContainer) {
            return null;
        }

        $pkgTypes = $this->getPkgTypes();
        $specialServiceId = array_key_exists($packageContainer, $pkgTypes) ? $pkgTypes[$packageContainer]['suggestedTrackableSpecialServiceId'] : null;

        if (null === $specialServiceId) {
            return null;
        }

        return [new SpecialService([
            'special_service_id' => $specialServiceId,
        ])];
    }

    public function getApiAccessTokenStatus(): bool
    {
        return (bool)$this->scopeConfig->getValue($this->xmlPathSettings['api_token_is_actual']);
    }

    public function setApiAccessTokenStatus(bool $isActual = true): self
    {
        $this->config->saveConfig($this->xmlPathSettings['api_token_is_actual'], $isActual);

        return $this;
    }
}
