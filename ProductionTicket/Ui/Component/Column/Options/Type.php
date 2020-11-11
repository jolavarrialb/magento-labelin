<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Ui\Component\Column\Options;

use Labelin\ProductionTicket\Model\ProductionTicket;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\Collection;
use Labelin\ProductionTicket\Model\ResourceModel\ProductionTicket\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Type implements OptionSourceInterface
{
    /** @var array */
    protected $options;

    /** @var CollectionFactory */
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        if ($this->options) {
            return $this->options;
        }

        $collection = $this
            ->initCollection()
            ->addFieldToFilter('type', ['notnull' => true]);

        $collection
            ->getSelect()
            ->group('type');

        if ($collection->getSize() === 0) {
            return [];
        }

        foreach ($collection as $item) {
            /** @var ProductionTicket $item */
            $this->options[] = [
                'value' => $item->getType(),
                'label' => $item->getType(),
            ];
        }

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
}
