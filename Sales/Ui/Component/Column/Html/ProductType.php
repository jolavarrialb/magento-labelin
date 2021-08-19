<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Column\Html;

use Magento\Ui\Component\Listing\Columns\Column;

class ProductType extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        $data = &$dataSource['data']['items'];

        foreach ($data as &$item) {
            $item['product_type'] = $this->getOrderProductTypes($item);
        }

        return $dataSource;
    }

    protected function getOrderProductTypes(array $item): array
    {
        $productTypes = $this->getArrayFromDbString($item['product_type']);

        if (!isset($item['is_reordered'])) {
            return $productTypes;
        }

        $reorderedData = $this->getArrayFromDbString($item['is_reordered']);

        if (count($reorderedData) > 1) {
            $productTypes[] = 'has_reordered_item';

            return $productTypes;
        }

        if (count($reorderedData) === 1 && $reorderedData[0]) {
            return ['reordered'];
        }

        return $productTypes;
    }

    protected function getArrayFromDbString($dbString): array
    {
        return explode(',', $dbString);
    }
}
