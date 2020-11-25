<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Observer\Config;

use Labelin\PitneyBowesRestApi\Model\Api\Oauth;
use Labelin\PitneyBowesShipping\Helper\Config\FixedPriceShippingConfig;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Save implements ObserverInterface
{
    /** @var Oauth */
    protected $oauth;

    /** @var FreeShippingConfig */
    protected $freeShippingConfig;

    /** @var FixedPriceShippingConfig */
    protected $fixedPriceShippingConfig;

    public function __construct(
        Oauth $oauth,
        FreeShippingConfig $freeShippingConfig,
        FixedPriceShippingConfig $fixedPriceShippingConfig
    ) {
        $this->oauth = $oauth;

        $this->freeShippingConfig = $freeShippingConfig;
        $this->fixedPriceShippingConfig = $fixedPriceShippingConfig;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \JsonException
     */
    public function execute(Observer $observer): self
    {
        $changedPaths = $observer->getEvent()->getData('changed_paths');

        if (!$changedPaths) {
            return $this;
        }

        if ($this->freeShippingConfig->getApiAccessToken() && $this->fixedPriceShippingConfig->getApiAccessToken()) {
            return $this;
        }

        $token = $this->oauth->getAuthToken();

        if (!$token) {
            return $this;
        }

        if (!$this->freeShippingConfig->getApiAccessToken()) {
            $this->freeShippingConfig->saveApiAccessToken($token);
        }

        if (!$this->fixedPriceShippingConfig->getApiAccessToken()) {
            $this->fixedPriceShippingConfig->saveApiAccessToken($token);
        }

        return $this;
    }
}
