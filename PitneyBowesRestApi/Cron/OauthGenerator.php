<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Cron;

use Labelin\PitneyBowesRestApi\Helper\CacheCleaner;
use Labelin\PitneyBowesRestApi\Model\Api\Oauth;
use Labelin\PitneyBowesShipping\Helper\Config\FixedPriceShippingConfig;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig;

class OauthGenerator
{
    /** @var array */
    protected $cacheCleantypes = [
        'config',
    ];

    /** @var Oauth */
    protected $oauth;

    /** @var FreeShippingConfig */
    protected $freeShippingConfig;

    /** @var FixedPriceShippingConfig */
    protected $fixedPriceShippingConfig;

    /** @var CacheCleaner */
    protected $cacheCleanerHelper;

    public function __construct(
        Oauth $oauth,
        FreeShippingConfig $freeShippingConfig,
        FixedPriceShippingConfig $fixedPriceShippingConfig,
        CacheCleaner $cacheCleanerHelper
    ) {
        $this->oauth = $oauth;

        $this->freeShippingConfig = $freeShippingConfig;
        $this->fixedPriceShippingConfig = $fixedPriceShippingConfig;
        $this->cacheCleanerHelper = $cacheCleanerHelper;
    }

    /**
     * @return $this
     * @throws \JsonException
     */
    public function execute(): self
    {
        $token = $this->oauth->getAuthToken();

        if (!$token) {
            $this->setActualApiTokensStatus(false);

            return $this;
        }

        $this->freeShippingConfig->saveApiAccessToken($token);
        $this->fixedPriceShippingConfig->saveApiAccessToken($token);

        $this->setActualApiTokensStatus();

        $this->cacheCleanerHelper->cacheClean($this->cacheCleantypes);

        return $this;
    }

    /**
     * @param bool $tokenIsActual
     */
    protected function setActualApiTokensStatus(bool $tokenIsActual = true): void
    {
        $this->freeShippingConfig->setApiAccessTokenStatus($tokenIsActual);
        $this->fixedPriceShippingConfig->setApiAccessTokenStatus($tokenIsActual);
    }
}
