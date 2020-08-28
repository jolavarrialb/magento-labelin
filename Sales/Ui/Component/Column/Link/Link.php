<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Column\Link;

use Magento\Framework\Serialize\Serializer\Json;

class Link extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected const OPTION_TYPE_FILE = 'file';

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $data = &$dataSource['data']['items'];

        $json = \Magento\Framework\App\ObjectManager::getInstance()->get(Json::class);
        if (!empty($data) && array_key_exists('order_configurable_product_options', current($data))) {
            foreach ($data as &$item) {
                $jsonUnSerialize = $json->unserialize($item['order_configurable_product_options']);
                if (!array_key_exists('options', $jsonUnSerialize)) {
                    continue;
                }
                foreach ($jsonUnSerialize['options'] as $option) {
                    if ($option['option_type'] === static::OPTION_TYPE_FILE){
                        $item['attached_image_label'] = __('Attached File');
                        $pattern = '/<a href="(.+)">/';
                        preg_match($pattern, $option['value'], $link);
                        $item['link'] = $link[1];
                    }
                }
            }
        }

        return $dataSource;
    }
}
