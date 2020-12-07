<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

use InvalidArgumentException;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Errors;
use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Shipment;
use Labelin\PitneyBowesOfficialApi\Model\ApiException;
use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\ParcelDtoInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\ShipmentsRatesDto;

interface ShipmentInterface
{
    /**
     * @param AddressDtoInterface $fromAddress
     * @param AddressDtoInterface $toAddress
     * @param ParcelDtoInterface $parcel
     * @param ShipmentsRatesDto $rates
     * @param string $x_pb_transaction_id
     * @return Shipment|Errors
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     */
    public function requestShipmentLabel(
        AddressDtoInterface $fromAddress,
        AddressDtoInterface $toAddress,
        ParcelDtoInterface $parcel,
        ShipmentsRatesDto $rates,
        string $x_pb_transaction_id
    );
}
