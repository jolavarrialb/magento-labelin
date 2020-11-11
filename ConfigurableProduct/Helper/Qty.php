<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\Helper;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\AbstractHelper;

class Qty extends AbstractHelper
{
    /** @var Product */
    protected $product;

    public function isAvailableAdvancedQty(): bool
    {
        if (!$this->getProduct()) {
            return false;
        }

        return (bool)$this->getAdvancedQty();
    }

    public function getAdvancedQty(): array
    {
        if (!$this->getProduct() || !$this->getProduct()->getTierPrices()) {
            return [];
        }

        return $this->getProduct()->getTierPrices();
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
