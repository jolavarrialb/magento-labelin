<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Carrier;

class FreeShipping extends AbstractPitneyBowesCarrier
{
    public const SHIPPING_CODE = 'pitneybowesfreeshipping';

    /** @var string */
    protected $_code = self::SHIPPING_CODE;
}
