<?php

declare(strict_types=1);

namespace Labelin\Sales\Plugin\Block\Adminhtml\Order\View;

use Labelin\Sales\Helper\Shipper as ShipperHelper;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class ShipperTabsHandler
{
    /** @var ShipperHelper */
    protected $shipperHelper;

    public function __construct(ShipperHelper $shipperHelper)
    {
        $this->shipperHelper = $shipperHelper;
    }

    public function afterCanShowTab(TabInterface $subject, bool $result): bool
    {
        if ($this->shipperHelper->isCurrentAuthUserShipper()) {
            return false;
        }

        return $result;
    }
}
