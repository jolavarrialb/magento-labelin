<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface ShipmentsRatesDtoInterface
{

    /**
     * @param string $carrier
     * @return mixed
     */
    public function setCarrier(string $carrier);

    /**
     * @return string
     */
    public function getCarrier(): string;

    /**
     * @param string $serviceId
     * @return mixed
     */
    public function setServiceId(string $serviceId);

    /**
     * @return string
     */
    public function getServiceId(): string;

    /**
     * @param string $parcelType
     * @return mixed
     */
    public function setParcelType(string $parcelType);

    /**
     * @return string
     */
    public function getParcelType(): string;

    /**
     * @param string $postalCode
     * @return mixed
     */
    public function setInductionPostalCode(string $postalCode);

    /**
     * @return string
     */
    public function getInductionPostalCode(): string;

    /**
     * @param SpecialServiceDtoInterface $specialServiceDto
     * @return mixed
     */
    public function addSpecialService(SpecialServiceDtoInterface $specialServiceDto);

    /**
     * @return array
     */
    public function getSpecialService(): array;
}
