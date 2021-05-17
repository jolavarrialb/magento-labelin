<?php

declare(strict_types=1);

namespace Labelin\PriceCurrency\Model\Directory\Plugin;

use Magento\Directory\Model\PriceCurrency as MagentoPriceCurrency;

class PriceRound
{
    protected const LABELIN_DEFAULT_PRECISION = 4;

    public function aroundConvertAndRound(
        MagentoPriceCurrency $subject,
        \Closure $proceed,
        $amount,
        $scope = null,
        $currency = null,
        $precision = self::LABELIN_DEFAULT_PRECISION
    ) {
        return $proceed($amount, $scope, $currency, $precision);
    }

    public function aroundFormat(
        MagentoPriceCurrency $subject,
        \Closure $proceed,
        $amount,
        $includeContainer = true,
        $precision = self::LABELIN_DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ) {
        return $proceed($amount, $includeContainer, $precision, $scope, $currency);
    }

    public function aroundConvertAndFormat(
        MagentoPriceCurrency $subject,
        \Closure $proceed,
        $amount,
        $includeContainer = true,
        $precision = self::LABELIN_DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ) {

        return $proceed($amount, $includeContainer, $precision, $scope, $currency);
    }

    public function aroundRound(
        MagentoPriceCurrency $subject,
        \Closure $proceed,
        $price
    ) {
        $precision = self::LABELIN_DEFAULT_PRECISION;

        return round($price, $precision);
    }

    public function aroundRoundPrice(
        MagentoPriceCurrency $subject,
        \Closure $proceed,
        $price,
        $precision = self::LABELIN_DEFAULT_PRECISION
    ) {
        return $proceed($price, $precision);
    }
}
