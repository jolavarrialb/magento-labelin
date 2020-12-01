<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\Address;
use Labelin\PitneyBowesRestApi\Api\Data\VerifiedAddressDtoInterface;

class VerifiedAddressDto extends AddressDto implements VerifiedAddressDtoInterface
{
    /** @var string */
    protected $deliveryPoint;

    /** @var string */
    protected $carrierRoute;

    /** @var string */
    protected $status;

    public function setDeliveryPoint(string $deliveryPoint): self
    {
        $this->deliveryPoint = $deliveryPoint;

        return $this;
    }

    public function getDeliveryPoint(): string
    {
        return $this->deliveryPoint;
    }

    public function setCarrierRoute(string $carrierRoute): self
    {
        $this->carrierRoute = $carrierRoute;

        return $this;
    }

    public function getCarrierRoute(): string
    {
        return $this->carrierRoute;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'deliveryPoint' => $this->getDeliveryPoint(),
                'carrierRoute' => $this->getCarrierRoute(),
                'status' => $this->getStatus(),
            ]
        );
    }

    public function isValid(): bool
    {
        return Address::STATUS_NOT_CHANGED !== $this->getStatus();
    }
}
