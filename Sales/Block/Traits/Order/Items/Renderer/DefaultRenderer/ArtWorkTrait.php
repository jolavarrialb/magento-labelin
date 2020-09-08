<?php

declare(strict_types=1);

namespace Labelin\Sales\Block\Traits\Order\Items\Renderer\DefaultRenderer;

trait ArtWorkTrait
{
    public function parseItemOptions(): void
    {
        $options = $this->getOrderOptions($this->getData('item'));

        if (!empty($options['option_value'])) {
            $this->optionValues = $this->json->unserialize($options['option_value']);
        }
    }

    public function getArtworkType(): string
    {
        return $this->optionValues['type'] ?? "";
    }

    public function getArtworkUrl(): string
    {
        $optionUrl = $this->optionValues['url'];

        return $this->url->getUrl($optionUrl['route'], $optionUrl['params']) ?? '#';
    }

    public function getArtworkLabel(): string
    {
        return $this->optionValues['title'] ?? "";
    }

    public function getArtworkWidth(): int
    {
        return $this->artworkOptionsHelper->getConfigHeight();
    }

    public function getArtworkHeight(): int
    {
        return $this->artworkOptionsHelper->getConfigWidth();
    }

    protected function getOrderOptions($item = null): array
    {
        $result = [];

        if (null === $item) {
            return $result;
        }

        $options = $item->getProductOptions();

        if (!$options || !isset($options['options'])) {
            return $result;
        }

        foreach ($options['options'] as $key => $option) {
            if (is_array($option) && !empty($option['option_type']) && $option['option_type'] === 'file') {
                $result = array_merge($result, $option);
            }
        }

        return $result;
    }
}
