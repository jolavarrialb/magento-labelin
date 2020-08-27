<?php

declare(strict_types=1);

namespace Labelin\Sales\Model;

use Magento\Sales\Model\Order as MagentoOrder;

class Order extends MagentoOrder
{
    public const STATUS_REVIEW        = 'designer_review';
    public const STATUS_IN_PRODUCTION = 'in_production';
    public const STATUS_OVERDUE       = 'overdue';

    public function canReview(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        return !in_array($this->getStatus(), [static::STATUS_REVIEW, static::STATUS_IN_PRODUCTION], false);
    }
}
