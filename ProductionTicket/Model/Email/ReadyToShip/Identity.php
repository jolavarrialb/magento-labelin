<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Email\ReadyToShip;

use Labelin\ProductionTicket\Model\ProductionTicket;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order\Email\Container\Container;
use Magento\Store\Model\StoreManagerInterface;
use Labelin\Sales\Helper\Shipper;

class Identity extends Container
{
    /** @var array */
    protected $xmlPathSettings;

    /** @var ProductionTicket */
    protected $productionTicket;

    /** @var Shipper  */
    protected $shipperHelper;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Shipper $shipperHelper,
        array $xmlPathSettings = []
    ) {
        parent::__construct($scopeConfig, $storeManager);

        $this->xmlPathSettings = $xmlPathSettings;
        $this->shipperHelper = $shipperHelper;
    }

    public function getProductionTicket(): ProductionTicket
    {
        return $this->productionTicket;
    }

    public function setProductionTicket(ProductionTicket $productionTicket): self
    {
        $this->productionTicket = $productionTicket;

        return $this;
    }

    public function isEnabled(): bool
    {
        return (bool)$this->getConfigValue($this->xmlPathSettings['is_enabled'], $this->getStore()->getStoreId());
    }

    public function getEmailCopyTo(): array
    {
        $emailCopyTo = [];
        $shippers = $this->shipperHelper->getShippersCollection();

        if ($shippers->getSize() === 0) {
            return $emailCopyTo;
        }

        $shippers->removeItemByKey($shippers->getFirstItem()->getId());

        foreach ($shippers as $shipper) {
            $emailCopyTo[] = $shipper->getData('email');
        }

        return $emailCopyTo;
    }

    public function getCopyMethod(): string
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['copy_method'], $this->getStore()->getStoreId());
    }

    public function getGuestTemplateId()
    {
        return $this->getConfigValue($this->xmlPathSettings['template'], $this->getStore()->getStoreId());
    }

    public function getTemplateId(): string
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['template'], $this->getStore()->getStoreId());
    }

    public function getEmailIdentity(): string
    {
        return (string)$this->getConfigValue($this->xmlPathSettings['identity'], $this->getStore()->getStoreId());
    }
}
