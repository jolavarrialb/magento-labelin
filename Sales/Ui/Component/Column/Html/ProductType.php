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
            $item['product_type'] = explode(',', $item['product_type']);
        }

        return $dataSource;
    }
}
