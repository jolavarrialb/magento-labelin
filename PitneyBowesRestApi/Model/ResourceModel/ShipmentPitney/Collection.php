<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitney;

use Labelin\PitneyBowesRestApi\Model\ResourceModel\ShipmentPitneyResource;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ShipmentPitney::class, ShipmentPitneyResource::class);
    }
}
