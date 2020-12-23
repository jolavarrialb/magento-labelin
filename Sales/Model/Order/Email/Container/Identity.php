<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Order\Email\Container;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order\Email\Container\Container;
use Magento\Store\Model\StoreManagerInterface;

class Identity extends Container
{
    /** @var array */
    protected $xmlPathSettings;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        array $xmlPathSettings = []
    ) {
        parent::__construct($scopeConfig, $storeManager);

        $this->xmlPathSettings = $xmlPathSettings;
    }

    public function isEnabled(): bool
    {
        return (bool)$this->getConfigValue($this->xmlPathSettings['is_enabled'], $this->getStore()->getStoreId());
    }

    public function getEmailCopyTo(): array
    {
        $data = $this->getConfigValue($this->xmlPathSettings['copy_to'], $this->getStore()->getStoreId());

        if (!empty($data)) {
            return array_map('trim', explode(',', $data));
        }

        return [];
    }

    public function getCopyMethod(): string
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['copy_method'], $this->getStore()->getStoreId());
    }

    public function getGuestTemplateId(): string
    {
        return $this->getConfigValue($this->xmlPathSettings['template'], $this->getStore()->getStoreId());
    }

    public function getTemplateId(): string
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['template'], $this->getStore()->getStoreId());
    }

    /**
     * @return mixed|string
     */
    public function getEmailIdentity()
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['identity'], $this->getStore()->getStoreId());
    }
}
