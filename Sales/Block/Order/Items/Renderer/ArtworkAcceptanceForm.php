<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Block\Common\ArtworkFormAbstract;

class ArtworkAcceptanceForm extends ArtworkFormAbstract
{
    public function isFormAvailable(): bool
    {
        $orderItem = $this->getOrderItem();

        if (!$orderItem) {
            return false;
        }

        if ($orderItem->isArtworkApproved()) {
            return false;
        }

        if (!$this->artworkHelper->isArtworkInReview($orderItem)) {
            return false;
        }

        if (!$orderItem->isArtworkApprovedByDesigner() && $orderItem->getArtworkDeclinesCount() === 0) {
            return true;
        }

        if (!$orderItem->isArtworkApprovedByDesigner() && $orderItem->getArtworkDeclinesCount()) {
            return false;
        }

        return parent::isFormAvailable();
    }
}
