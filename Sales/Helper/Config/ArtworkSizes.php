<?php

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ArtworkSizes extends AbstractHelper
{
    protected const XML_PATH_ARTWORK_WIDTH = 'labelin_sales/artwork_configuration/order_items_grid_width_size';
    protected const XML_PATH_ARTWORK_HEIGHT = 'labelin_sales/artwork_configuration/order_items_grid_height_size';

    public function getConfigWidth($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_ARTWORK_WIDTH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getConfigHeight($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_ARTWORK_HEIGHT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
