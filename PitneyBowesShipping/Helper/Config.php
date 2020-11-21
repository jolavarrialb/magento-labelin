<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    protected const XML_PATH_CARRIERS_PITNEYBOWES_API_KEY    = 'carriers/pitneybowesfreeshipping/api_key';
    protected const XML_PATH_CARRIERS_PITNEYBOWES_API_SECRET = 'carriers/pitneybowesfreeshipping/api_secret';
    protected const XML_PATH_CARRIERS_PITNEYBOWES_API_URL    = 'carriers/pitneybowesfreeshipping/api_url';

    /** @var EncryptorInterface */
    protected $encryptor;

    public function __construct(Context $context, EncryptorInterface $encryptor)
    {
        parent::__construct($context);

        $this->encryptor = $encryptor;
    }

    public function getCode(string $type): array
    {
        $codes = $this->getCodes();

        if (!isset($codes[$type])) {
            return [];
        }

        return $codes[$type];
    }

    public function getApiKey($storeId = null): string
    {
        $value = $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_PITNEYBOWES_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->encryptor->decrypt($value);
    }

    public function getApiSecret($storeId = null): string
    {
        $value = $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_PITNEYBOWES_API_SECRET,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->encryptor->decrypt($value);
    }

    public function getApiUrl($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_PITNEYBOWES_API_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    protected function getCodes(): array
    {
        return [
            'container' => [
                'PKG' => '00',
            ],
            'container_description' => [
                'PKG' => __('Customer Packaging'),
            ],
            'unit_of_measure' => [
                'LBS' => __('Pounds'),
                'KGS' => __('Kilograms'),
                'OZ' => __('Ounces'),
            ],
            'method' => [
                'USPS' => __('USPS'),
                'UPS' => __('UPS'),
            ],
        ];
    }
}
