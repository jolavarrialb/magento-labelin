<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

use InvalidArgumentException;
use Labelin\PitneyBowesOfficialApi\Model\ApiException;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;
use Magento\Framework\DataObject;

interface ShipmentInterface
{
    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface $parcel
     * @param ShipmentsRatesDto $rates
     * @param DataObject $request
     * @return DataObject
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     */
    public function requestShipmentLabel(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel,
        ShipmentsRatesDto $rates,
        DataObject $request
    );
}
