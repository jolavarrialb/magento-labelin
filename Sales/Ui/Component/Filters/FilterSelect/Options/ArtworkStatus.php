<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Filters\FilterSelect\Options;

use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Api\Filter;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;

class ArtworkStatus implements OptionSourceInterface
{
    protected $options = [];

    /** @var Filter */
    protected $filter;

    /** @var CollectionFactory */
    protected $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        $collection = $this
            ->initCollection()
            ->addFieldToSelect(Item::ARTWORK_STATUS)
            ->addFieldToFilter(Item::ARTWORK_STATUS, ['notnull' => true]);

        $collection->distinct(true);

        foreach ($collection as $item) {
            $this->options[] = [
                'value' => $item->getData(Item::ARTWORK_STATUS),
                'label' => $this->getLabel($item->getData(Item::ARTWORK_STATUS)),
            ];
        }

        $this->filter->setField(Item::ARTWORK_STATUS)->setConditionType('notnull');

        return $this->options;
    }

    /**
     * @param array $data
     *
     * @return Collection|SearchResult
     */
    protected function initCollection(array $data = [])
    {
        return $this->collectionFactory->create($data);
    }

    protected function getLabel(string $status): string
    {
        $status = str_replace('_', ' ', $status);

        return ucwords($status);
    }
}
