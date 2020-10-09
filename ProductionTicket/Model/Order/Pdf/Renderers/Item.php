<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Pdf\Renderers;

use Magento\Sales\Model\Order\Pdf\Items\AbstractItems;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;

class Item extends AbstractItems
{
    /** @var \Magento\Framework\Stdlib\StringUtils */
    protected $string;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );

        $this->string = $string;
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function draw()
    {
        $order = $this->getOrder();
        /** @var \Labelin\Sales\Model\Order\Item $item */
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();

        $this->_setFontRegular();

        $lines = [];

        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName(), 35, true, true), 'feed' => 35]];

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 17),
            'feed' => 255,
            'align' => 'right',
        ];

        // draw QTY
        $qty = $item->getQty() ?? $item->getQtyOrdered();
        $lines[0][] = ['text' => $qty * 1, 'feed' => 445, 'font' => 'bold', 'align' => 'right'];

        // draw options
        $options = $this->getItemOptions($item);
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                // draw options value
                $printValue = isset(
                    $option['print_value']
                ) ? $option['print_value'] : $this->filterManager->stripTags(
                    $option['value']
                );
                $lines[][] = ['text' => $this->string->split($printValue, 30, true, true), 'feed' => 40];
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }
    /*
     * $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();

        $lines = [];

        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName(), 35, true, true), 'feed' => 35]];

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 17),
            'feed' => 255,
            'align' => 'right',
        ];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 445, 'font' => 'bold', 'align' => 'right'];

        // draw options
        $options = $this->getItemOptions($item);
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                // draw options value
                $printValue = isset(
                    $option['print_value']
                ) ? $option['print_value'] : $this->filterManager->stripTags(
                    $option['value']
                );
                $lines[][] = ['text' => $this->string->split($printValue, 30, true, true), 'feed' => 40];
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
     */
}
