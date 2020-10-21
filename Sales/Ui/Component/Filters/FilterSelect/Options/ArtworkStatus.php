<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Filters\FilterSelect\Options;

use Labelin\Sales\Helper\Artwork;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Api\Filter;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;

class ArtworkStatus implements OptionSourceInterface
{
    /** @var array */
    protected $options = [];

    /** @var Artwork */
    protected $artworkHelper;

    public function __construct(
        Artwork $artworkHelper
    ) {
        $this->artworkHelper = $artworkHelper;
    }

    public function toOptionArray(): array
    {
        foreach ($this->artworkHelper::FILTER_STATUSES as $status) {
            $this->options[] = [
                'value' => $status,
                'label' => $this->getLabel($status),
            ];
        }

        return $this->options;
    }

    protected function getLabel(string $status): string
    {
        $status = str_replace('_', ' ', $status);

        return ucwords($status);
    }
}
