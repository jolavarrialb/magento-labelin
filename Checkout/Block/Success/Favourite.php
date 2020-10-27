<?php

declare(strict_types=1);

namespace Labelin\Checkout\Block\Success;

use Magento\Checkout\Block\Onepage\Success;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class Favourite extends Success
{
    /** @var ModuleManager */
    protected $moduleManager;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var CustomerSession */
    protected $customerSession;

    public function __construct(
        Context $context,
        Session $checkoutSession,
        Order\Config $orderConfig,
        HttpContext $httpContext,
        ModuleManager $moduleManager,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);

        $this->moduleManager = $moduleManager;
        $this->customerSession = $customerSession;

        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function isAvailable(): bool
    {
        return (bool)$this->getOrderId() &&
            $this->customerSession->isLoggedIn() &&
            $this->moduleManager->isEnabled('Labelin_Sales');
    }

    public function getFavouriteUrl(): string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $this->getOrderId(), 'eq')
            ->create();

        $ordersList = $this->orderRepository->getList($searchCriteria)->getItems();

        /** @var Order $order */
        $order = current($ordersList);

        return $this->getUrl('sales/order_favourite/add', ['id' => $order->getId()]);
    }
}
