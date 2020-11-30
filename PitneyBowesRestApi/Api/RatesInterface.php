<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;

interface RatesInterface
{
    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface  $parcel
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\RateDtoInterface[]
     */
    public function requestRates(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel
    );
}
