<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Column\Link;

use Labelin\Sales\Helper\Artwork;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Link extends Column
{
    /** @var Json */
    protected $json;

    /** @var string */
    protected $regexPattern = '/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU';

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Json $json,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->json = $json;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $data = &$dataSource['data']['items'];

        if (!empty($data) && array_key_exists('order_configurable_product_options', current($data))) {
            foreach ($data as &$item) {
                $jsonUnSerialize = $this->json->unserialize($item['order_configurable_product_options']);

                if (!array_key_exists('options', $jsonUnSerialize)) {
                    continue;
                }

                foreach ($jsonUnSerialize['options'] as $option) {
                    if ($option['option_type'] === Artwork::FILE_OPTION_TYPE) {
                        preg_match($this->regexPattern, $option['value'], $linkMatch);
                        $item['link'] = $linkMatch[1] ?? '#';
                        $item['attached_image_label'] = strip_tags($option['value']) ?? __('Attached image');
                    }
                }
            }
        }

        return $dataSource;
    }
}
