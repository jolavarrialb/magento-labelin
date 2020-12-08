<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\SpecialServiceDtoInterface;

class SpecialServiceDto implements SpecialServiceDtoInterface
{
    /** @var string */
    protected $specialServiceId = '';

    /** @var array */
    protected $inputParameters = [];

    public function setSpecialServiceId(string $specialServiceId): self
    {
        $this->specialServiceId = $specialServiceId;

        return $this;
    }

    public function getSpecialServiceId(): string
    {
        return $this->specialServiceId;
    }

    public function setInputParameters(array $inputParameters): self
    {
        $this->inputParameters = $inputParameters;

        return $this;
    }

    public function getInputParameters(): array
    {
        return $this->inputParameters;
    }
}
