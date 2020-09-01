<?php

declare(strict_types=1);

namespace Labelin\Sales\Ui\Component\Column\Html;

use Labelin\Sales\Helper\Artwork;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Artworks extends Column
{
    /** @var Json */
    protected $json;

    /** @var Artwork */
    protected $artworkHelper;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Json $json,
        Artwork $artworkHelper,
        OrderRepositoryInterface $orderRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->json = $json;
        $this->artworkHelper = $artworkHelper;
        $this->orderRepository = $orderRepository;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        $data = &$dataSource['data']['items'];

        foreach ($data as &$item) {
            $order = $this->orderRepository->get($item['entity_id']);
            $item['order_artworks'] = $this->artworkHelper->getOrderArtworks($order);
        }

        return $dataSource;
    }
}
