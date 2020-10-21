<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Adminhtml\Order;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Helper\Designer as DesignerHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;

abstract class MassDesignerAbstract extends Action
{
    /*** @var DesignerHelper */
    protected $designerHelper;

    /*** @var OrderFactory */
    protected $orderFactory;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var Artwork */
    protected $artworkHelper;

    public function __construct(
        Context $context,
        DesignerHelper $designerHelper,
        OrderRepositoryInterface $orderRepository,
        Artwork $artworkHelper,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->designerHelper = $designerHelper;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->artworkHelper = $artworkHelper;

        parent::__construct($context);
    }

    protected function _isAllowed(): bool
    {
        return !$this->designerHelper->isCurrentAuthUserDesigner();
    }
}
