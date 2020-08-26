<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\MassAction\Designer;

use Labelin\Sales\Helper\Designer as Helper;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\User\Model\User;

class Assign implements \JsonSerializable
{
    /*** @var array */
    protected $options;

    /*** @var array */
    protected $data;

    /*** @var UrlInterface */
    protected $urlBuilder;

    /*** @var string */
    protected $urlPath;

    /*** @var string */
    protected $paramName;

    /*** @var array */
    protected $additionalData = [];

    /*** @var Helper */
    protected $helper;

    public function __construct(UrlInterface $urlBuilder, Helper $helper, array $data = [])
    {
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
        $this->data = $data;
    }

    public function jsonSerialize(): array
    {
        if ($this->options !== null) {
            return $this->options;
        }

        if ($this->helper->getDesignersCollection()->count() === 0) {
            return $this->options;
        }

        $options = [];

        foreach ($this->helper->getDesignersCollection() as $designer) {
            /** @var User $designer */
            $options[] = [
                'value' => $designer->getId(),
                'label' => $designer->getName() . ' (' . $designer->getUserName() . ')',
            ];
        }

        $this->prepareData();

        foreach ($options as $optionCode) {
            $this->options[$optionCode['value']] = [
                'type'          => 'customer_group_' . $optionCode['value'],
                'label'         => __($optionCode['label']),
                '__disableTmpl' => true,
            ];

            if ($this->urlPath && $this->paramName) {
                $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                    $this->urlPath,
                    [$this->paramName => $optionCode['value']]
                );
            }

            $this->options[$optionCode['value']] = array_merge_recursive(
                $this->options[$optionCode['value']],
                $this->additionalData
            );
        }

        $this->options = array_values($this->options);

        return $this->options;
    }

    protected function prepareData(): void
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                case 'confirm':
                    foreach ($value as $messageName => $message) {
                        $this->additionalData[$key][$messageName] = (string)new Phrase($message);
                    }
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
