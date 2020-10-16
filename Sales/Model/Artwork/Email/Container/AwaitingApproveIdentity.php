<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Container;

use Labelin\Sales\Helper\Config\ArtworkAwaitingCustomerApprove;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class AwaitingApproveIdentity extends Identity
{
    /** @var ArtworkAwaitingCustomerApprove */
    protected $awaitingCustomerApprove;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ArtworkAwaitingCustomerApprove $awaitingCustomerApprove,
        array $xmlPathSettings = []
    ) {
        parent::__construct($scopeConfig, $storeManager, $xmlPathSettings);

        $this->awaitingCustomerApprove = $awaitingCustomerApprove;
    }

    public function isEnabled(): bool
    {
        return $this->awaitingCustomerApprove->isEnabled();
    }

    public function getExceededDays(): int
    {
        return $this->awaitingCustomerApprove->getExceededDays();
    }
}
