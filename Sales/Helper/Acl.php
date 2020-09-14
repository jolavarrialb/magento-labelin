<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\AuthorizationInterface;

class Acl extends AbstractHelper
{
    protected const ACL_LABELIN_SALES = 'Labelin_Sales::sales_order';
    protected const ACL_ORDERS_CHART  = 'Labelin_Sales::orders_chart';

    /** @var AuthorizationInterface */
    protected $authorization;

    public function __construct(Context $context, AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
        parent::__construct($context);
    }

    public function isAllowedAclLabelinSales(): bool
    {
        return $this->authorization->isAllowed(static::ACL_LABELIN_SALES);
    }

    public function isAllowedAclOrdersChart(): bool
    {
        return $this->authorization->isAllowed(static::ACL_ORDERS_CHART);
    }
}
