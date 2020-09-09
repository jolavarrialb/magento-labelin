<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Block\Common\ArtworkFormAbstract;

class ArtworkAcceptanceForm extends ArtworkFormAbstract
{
    public function getSubmitUrl(): string
    {
        if (!$this->getOrderItem()) {
            return '';
        }

        return $this->getUrl('sales/order_item/updateArtwork', ['item_id' => $this->getOrderItem()->getId()]);
    }
}
