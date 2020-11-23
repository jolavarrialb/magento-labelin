<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\ResourceModel;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyResourceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShipmentPitneyResource extends AbstractDb implements ShipmentPitneyResourceInterface
{
    protected const RESOURCE_MAIN_TABLE = 'labelin_shipment_pitney';

    protected function _construct()
    {
        $this->_init(self::RESOURCE_MAIN_TABLE, 'entity_id');
    }
}
