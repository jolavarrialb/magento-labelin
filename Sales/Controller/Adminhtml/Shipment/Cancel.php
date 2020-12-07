<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Shipment;

use Labelin\PitneyBowesRestApi\Model\Api\CancelShipment;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Cancel extends Action
{
    /** @var CancelShipment */
    protected $cancelShipment;

    public function __construct(Action\Context $context, CancelShipment $cancelShipment)
    {
        parent::__construct($context);

        $this->cancelShipment = $cancelShipment;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if (!$this->isAvailableProcessing()) {
            $this->messageManager->addErrorMessage(__('Please specify shipment'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $response = $this->cancelShipment->cancelShipment((int)$this->getRequest()->getParam('shipment_id'));

        if ($response) {
            $this
                ->messageManager
                ->addSuccessMessage(__('Request about cancellation is successfully sent to Pitney Bowes'));
        } else {
            $this
                ->messageManager
                ->addErrorMessage(__('Error. Check logs on server by the path: /var/log/pitney_bowes_api_debug.log'));
        }

        return $this->_redirect('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);
    }

    protected function isAvailableProcessing(): bool
    {
        return $this->getRequest()->getParam('shipment_id') && $this->getRequest()->getParam('order_id');
    }
}
