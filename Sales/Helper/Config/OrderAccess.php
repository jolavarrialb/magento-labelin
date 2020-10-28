<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Config;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\User\Model\User;

class OrderAccess extends AbstractHelper
{
    protected const XML_PATH_SHIPMENT_ACCESS_GROUP_ROLE = 'labelin_order_access/shipment/user_role';
    protected const XML_PATH_INVOICE_ACCESS_GROUP_ROLE  = 'labelin_order_access/invoice/user_role';
    protected const XML_PATH_CANCEL_ACCESS_GROUP_ROLE   = 'labelin_order_access/cancel/user_role';

    /** @var Session */
    protected $authSession;

    public function __construct(Context $context, Session $authSession)
    {
        parent::__construct($context);

        $this->authSession = $authSession;
    }

    public function isAllowedShipment(): bool
    {
        if (!$this->getCurrentAuthUser()) {
            return false;
        }

        return in_array(
            $this->getCurrentAuthUser()->getAclRole(),
            $this->getRolesByPath(static::XML_PATH_SHIPMENT_ACCESS_GROUP_ROLE),
            false
        );
    }

    public function isAllowedInvoicing(): bool
    {
        if (!$this->getCurrentAuthUser()) {
            return false;
        }

        return in_array(
            $this->getCurrentAuthUser()->getAclRole(),
            $this->getRolesByPath(static::XML_PATH_INVOICE_ACCESS_GROUP_ROLE),
            false
        );
    }

    public function isAllowedCancellation(): bool
    {
        if (!$this->getCurrentAuthUser()) {
            return false;
        }

        return in_array(
            $this->getCurrentAuthUser()->getAclRole(),
            $this->getRolesByPath(static::XML_PATH_CANCEL_ACCESS_GROUP_ROLE),
            false
        );
    }

    public function getCurrentAuthUser(): ?User
    {
        return $this->authSession->getUser();
    }

    protected function getRolesByPath(string $path = ''): array
    {
        $roles = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        if (!empty($roles)) {
            return array_map('trim', explode(',', $roles));
        }

        return [];
    }
}
