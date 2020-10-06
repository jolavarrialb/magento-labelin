<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Ui\Component\Column\Options;

use Labelin\ProductionTicket\Model\Order\Item;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AttributeInterface;
use Magento\Framework\Exception\LocalizedException;

class Size extends AbstractAttributeOption
{
    /**
     * @return AttributeInterface|null
     * @throws LocalizedException
     */
    public function getAttribute(): ?AttributeInterface
    {
        return $this->eavConfig->getAttribute(Product::ENTITY, Item::ATTRIBUTE_CODE_STICKER_SIZE);
    }
}
