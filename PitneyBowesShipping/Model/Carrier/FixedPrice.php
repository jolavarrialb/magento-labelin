<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Model\Carrier;

class FixedPrice extends AbstractPitneyBowesCarrier
{
    public const SHIPPING_CODE = 'pitneybowesfixedpriceshipping';

    /** @var string */
    protected $_code = self::SHIPPING_CODE;
}
