<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;

interface VerifyAddressInterface
{
    /**
     * @param AddressDtoInterface $address
     *
     * @return \Labelin\PitneyBowesRestApi\Api\Data\VerifiedAddressDtoInterface|string
     */
    public function verifyAddress(AddressDtoInterface $address);
}
