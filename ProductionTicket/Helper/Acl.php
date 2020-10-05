<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\AuthorizationInterface;

class Acl extends AbstractHelper
{
    protected const ACL_PRODUCTION_TICKET = 'Labelin_ProductionTicket::production_ticket';
    protected const ACL_PRODUCTION_TICKET_GRID = 'Labelin_ProductionTicket::production_ticket_grid';

    /** @var AuthorizationInterface */
    protected $authorization;

    public function __construct(Context $context, AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
        parent::__construct($context);
    }

    public function isAllowedAclProductionTicket(): bool
    {
        return $this->authorization->isAllowed(static::ACL_PRODUCTION_TICKET);
    }

    public function isAllowedAclProductionTicketGrid(): bool
    {
        return $this->authorization->isAllowed(static::ACL_PRODUCTION_TICKET_GRID);
    }
}
