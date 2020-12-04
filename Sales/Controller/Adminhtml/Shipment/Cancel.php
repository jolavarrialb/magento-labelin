<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Shipment;

use Labelin\PitneyBowesRestApi\Model\Api\CancelShipment;
use Magento\Backend\App\Action;

class Cancel extends Action
{
    /** @var CancelShipment */
    protected $cancelShipment;

    public function __construct(Action\Context $context, CancelShipment $cancelShipment)
    {
        parent::__construct($context);

        $this->cancelShipment = $cancelShipment;
    }

    public function execute()
    {
        $shipmentId = $this->getRequest()->getParam('shipment_id');

        if ($shipmentId) {
            $this->cancelShipment->cancelShipment((int)$shipmentId);
        }
    }
}
