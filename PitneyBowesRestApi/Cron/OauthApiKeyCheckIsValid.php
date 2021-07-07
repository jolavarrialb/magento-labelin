<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Cron;

use Labelin\PitneyBowesShipping\Helper\Config\FixedPriceShippingConfig;
use Labelin\PitneyBowesShipping\Helper\Config\FreeShippingConfig;

class OauthApiKeyCheckIsValid
{
    /** @var OauthGenerator */
    protected $oauthGenerator;

    /** @var FreeShippingConfig */
    protected $freeShippingConfig;

    /** @var FixedPriceShippingConfig */
    protected $fixedPriceShippingConfig;

    public function __construct(
        OauthGenerator $oauthGenerator,
        FreeShippingConfig $freeShippingConfig,
        FixedPriceShippingConfig $fixedPriceShippingConfig
    ) {
        $this->oauthGenerator = $oauthGenerator;
        $this->freeShippingConfig = $freeShippingConfig;
        $this->fixedPriceShippingConfig = $fixedPriceShippingConfig;
    }

    /**
     * @throws \JsonException
     */
    public function execute(): self
    {
        if ($this->freeShippingConfig->getApiAccessTokenStatus() &&
            $this->fixedPriceShippingConfig->getApiAccessTokenStatus()) {

            return $this;
        }

        $this->oauthGenerator->execute();

        return $this;
    }
}
