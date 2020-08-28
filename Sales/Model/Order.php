<?php

declare(strict_types=1);

namespace Labelin\Sales\Model;

use Magento\Framework\Exception\LocalizedException;
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

    public function canOverdue(): bool
    {
        if (!in_array($this->getState(), [static::STATE_PROCESSING, static::STATE_NEW], false)) {
            return false;
        }

        return in_array($this->getStatus(), static::getOverdueAvailableStatuses(), false);
    }

    public static function getOverdueAvailableStatuses(): array
    {
        return [
            static::STATE_PROCESSING,
            static::STATE_NEW,
            static::STATE_HOLDED,
            static::STATUS_REVIEW,
        ];
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsReview(): self
    {
        if (!$this->canReview()) {
            throw new LocalizedException(__('A review action is not available.'));
        }

        $this
            ->setStatus(static::STATUS_REVIEW)
            ->addStatusToHistory(static::STATUS_REVIEW, __('Order putted on review'));

        return $this;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function markAsOverdue(): self
    {
        if (!$this->canOverdue()) {
            throw new LocalizedException(__('An overdue action is not available.'));
        }

        $this
            ->setStatus(static::STATUS_OVERDUE)
            ->addStatusToHistory(static::STATUS_OVERDUE, __('Order putted on overdue'));

        return $this;
    }
}
