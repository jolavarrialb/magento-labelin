<?php

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ArtworkImgOptions extends AbstractHelper
{
    protected const XML_PATH_ARTWORK_ORDER_ITEM_GRID_WIDTH = 'labelin_sales/artwork_configuration/order_items_grid_width_size';
    protected const XML_PATH_ARTWORK_ORDER_ITEM_GRID_HEIGHT = 'labelin_sales/artwork_configuration/order_items_grid_height_size';


    public function getArtworkComponentScopeConfigOptions($storeId = null): array
    {
        return [
            'width' => $this->getScopeConfigOptions(static::XML_PATH_ARTWORK_ORDER_ITEM_GRID_WIDTH, $storeId),
            'height' => $this->getScopeConfigOptions(static::XML_PATH_ARTWORK_ORDER_ITEM_GRID_HEIGHT, $storeId),
        ];
    }

    protected function getScopeConfigOptions($xmlPatch, $storeId): int
    {
        return (int)$this->scopeConfig->getValue(
            $xmlPatch,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
