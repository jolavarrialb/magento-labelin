<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;

class PremadeToProduction extends Action
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    public function __construct(
        Action\Context $context,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute()
    {
        $orderIds = $this->getRequest()->getParam('selected');

        if (empty($orderIds)) {
            $this->messageManager->addErrorMessage(__('Nothing to move. Please select orders rows'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $orderIds, 'in')
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        foreach ($orders as $order) {
            $this->_eventManager->dispatch('labelin_premade_order_in_production', ['order' => $order]);
        }

        $this->messageManager->addSuccessMessage(__('Premade items in %1 order(s) was move to the production.', count($orderIds)));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
