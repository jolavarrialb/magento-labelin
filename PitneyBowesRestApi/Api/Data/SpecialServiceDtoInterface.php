<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface SpecialServiceDtoInterface
{
    /**
     * @param string $specialServiceId
     * @return mixed
     */
    public function setSpecialServiceId(string $specialServiceId);

    /**
     * @return string
     */
    public function getSpecialServiceId(): string;

    /**
     * @param array $inputParameters
     * @return mixed
     */
    public function setInputParameters(array $inputParameters);

    /**
     * @return array
     */
    public function getInputParameters(): array;
}
