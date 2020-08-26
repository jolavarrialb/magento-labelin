<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order;

use Labelin\Sales\Helper\Designer as Helper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;

abstract class MassDesignerAbstract extends Action
{
    /*** @var Helper */
    protected $helper;

    /*** @var Session */
    protected $authSession;

    /*** @var OrderFactory */
    protected $orderFactory;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    public function __construct(
        Context $context,
        Helper $helper,
        Session $authSession,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->helper = $helper;
        $this->authSession = $authSession;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context);
    }

    protected function _isAllowed(): bool
    {
        if (!$this->authSession->getUser()) {
            return false;
        }

        return $this->authSession->getUser()->getRole()->getId() !== $this->helper->getDesignerRole()->getId();
    }
}
