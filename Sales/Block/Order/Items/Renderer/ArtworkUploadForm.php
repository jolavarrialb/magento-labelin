<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Order\Items\Renderer;

use Labelin\Sales\Block\Common\ArtworkFormAbstract;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class ArtworkUploadForm extends ArtworkFormAbstract
{
    public function isFormAvailable(): bool
    {
        $orderItem = $this->getOrderItem();

        if (!$orderItem || $orderItem->getProductType() !== Configurable::TYPE_CODE) {
            return false;
        }

        return !$this->artworkHelper->isArtworkAttachedToOrderItem($orderItem);
    }

    public function getSubmitUrl(): string
    {
        if (!$this->getOrderItem()) {
            return '';
        }

        return $this->getUrl('sales/order_item/uploadArtwork', ['item_id' => $this->getOrderItem()->getId()]);
    }
}
