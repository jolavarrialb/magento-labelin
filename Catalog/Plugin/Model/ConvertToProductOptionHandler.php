<?php


namespace Labelin\Catalog\Plugin\Model;


use Magento\Catalog\Model\CustomOptions\CustomOptionFactory;
use Magento\Catalog\Model\ProductOptionProcessor;
use Magento\Framework\DataObject;

class ConvertToProductOptionHandler
{
    /** @var CustomOptionFactory */
    protected $customOptionFactory;

    public function __construct(
        CustomOptionFactory $customOptionFactory
    ) {
        $this->customOptionFactory = $customOptionFactory;
    }

    public function aroundConvertToProductOption(ProductOptionProcessor $subject, callable $proceed, DataObject $request): array
    {
        $options = $request->getOptions();
        if (!empty($options) && is_array($options)) {
            $data = [];
            foreach ($options as $optionId => $optionValue) {
                $option = $this->customOptionFactory->create();
                $option->setOptionId($optionId)->setOptionValue($optionValue);
                $data[] = $option;
            }

            return ['custom_options' => $data];
        }

        return [];
    }
}
