<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Cron;

use Labelin\PitneyBowesRestApi\Model\Api\Oauth;
use Labelin\PitneyBowesShipping\Helper\Config\FixedPriceShippingConfig;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig;

class OauthGenerator
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
     * @return $this
     * @throws \JsonException
     */
    public function execute(): self
    {
        $token = $this->oauth->getAuthToken();

        if (!$token) {
            return $this;
        }

        $this->freeShippingConfig->saveApiAccessToken($token);
        $this->fixedPriceShippingConfig->saveApiAccessToken($token);

        return $this;
    }
}
