<?php

declare(strict_types=1);

namespace Labelin\Sales\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ArtworkDecline extends AbstractHelper
{
    protected const XML_PATH_LABELIN_ARTWORK_MAX_DECLINES_COUNT = 'labelin_sales/artwork_configuration/declines_max_count';

    public function hasArtworkUnlimitedDeclines($storeId = null): bool
    {
        return !$this->getDeclinesQty($storeId);
    }

    public function getDeclinesQty($storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            static::XML_PATH_LABELIN_ARTWORK_MAX_DECLINES_COUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
