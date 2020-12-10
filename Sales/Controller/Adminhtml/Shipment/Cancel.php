<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Shipment;

use Labelin\PitneyBowesRestApi\Api\Data\ShipmentPitneyRepositoryInterface;
use Labelin\PitneyBowesRestApi\Model\Api\CancelShipment;
use Labelin\PitneyBowesRestApi\Model\ShipmentPitney;
use Magento\Backend\App\Action;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Cancel extends Action
{
    /** @var CancelShipment */
    protected $cancelShipment;

    /** @var ShipmentPitneyRepositoryInterface */
    protected $shipmentPitneyBowesRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;


    public function __construct(
        Action\Context $context,
        CancelShipment $cancelShipment,
        ShipmentPitneyRepositoryInterface $shipmentPitneyRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct($context);

        $this->cancelShipment = $cancelShipment;
        $this->shipmentPitneyBowesRepository = $shipmentPitneyRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if (!$this->isAvailableProcessing()) {
            $this->messageManager->addErrorMessage(__('Please specify shipment.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $shipmentId = (int)$this->getRequest()->getParam('shipment_id');

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('shipment_id', $shipmentId, 'eq')
            ->create();

        $shipments = $this->shipmentPitneyBowesRepository->getList($searchCriteria)->getItems();

        if (empty($shipments)) {
            $this->messageManager->addErrorMessage(__('Not found Pitney Bowes shipments.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        foreach ($shipments as $shipment) {
            /** @var ShipmentPitney $shipment */
            $pitneyBowesShipmentId = json_decode($shipment->getResponse(), true);
            $pitneyBowesShipmentId = $pitneyBowesShipmentId['shipmentId'];

            $response = $this->cancelShipment->cancelShipment($pitneyBowesShipmentId, $shipmentId);

            if ($response) {
                $this
                    ->messageManager
                    ->addSuccessMessage(__('Request about cancellation is successfully sent to Pitney Bowes.'));

                $this->shipmentPitneyBowesRepository->delete($shipment);
            } else {
                $this
                    ->messageManager
                    ->addErrorMessage(__('Error.Check log on server by the path: var/log/pitney_bowes_api_debug.log'));
            }
        }

        return $this->_redirect('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);
    }

    protected function isAvailableProcessing(): bool
    {
        return $this->getRequest()->getParam('shipment_id') && $this->getRequest()->getParam('order_id');
    }
}
