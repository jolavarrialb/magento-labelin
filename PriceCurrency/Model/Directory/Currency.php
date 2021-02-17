<?php

declare(strict_types=1);

namespace Labelin\PriceCurrency\Model\Directory;

use Magento\Directory\Model\Currency as MagentoCurrency;
use Zend_Currency_Exception;

class Currency extends MagentoCurrency
{
    public const DEFAULT_PRECISION = 3;

    /**
     * @param float $price
     * @param array $options
     *
     * @return string
     *
     * @throws Zend_Currency_Exception
     */
    public function formatTxt($price, $options = [])
    {
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        $price = sprintf("%F", $price);

        $options['precision'] = static::DEFAULT_PRECISION;

        return $this->_localeCurrency->getCurrency($this->getCode())->toCurrency($price, $options);
    }

    /**
     * @param float $price
     * @param array $options
     * @param bool $includeContainer
     * @param false $addBrackets
     *
     * @return string
     */
    public function format($price, $options = [], $includeContainer = true, $addBrackets = false)
    {
        return $this->formatPrecision($price, static::DEFAULT_PRECISION, $options, $includeContainer, $addBrackets);
    }
}
