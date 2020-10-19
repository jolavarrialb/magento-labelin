<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Pdf;

use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Sales\Model\Order\Pdf\AbstractPdf;
use Magento\Sales\Model\Order\Pdf\Config;
use Zend_Pdf;
use Zend_Pdf_Exception;
use Labelin\ProductionTicket\Model\Order\Item as OrderItem;
use Zend_Pdf_Page;

class Item extends AbstractPdf
{
    protected const PARAM_ITEM = 'item';

    protected const RENDERER_TYPE_ITEM = 'productionTicketItem';

    protected const RENDERER_TYPE_ITEM_IMAGE = 'productionTicketArtworkImage';

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /** @var Database|null */
    protected $fileStorageDatabase;

    /** @var \Magento\Framework\Module\Dir\Reader */
    protected $moduleReader;

    /** @var \Magento\Framework\Filter\FilterManager */
    protected $filterManager;

    public function __construct(
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        Config $pdfConfig,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        array $data = [],
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Database $fileStorageDatabase = null,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filter\FilterManager $filterManager
    ) {
        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $data,
            $fileStorageDatabase
        );

        $this->fileStorageDatabase = $fileStorageDatabase ?:
            \Magento\Framework\App\ObjectManager::getInstance()->get(Database::class);
        $this->storeManager = $storeManager;
        $this->moduleReader = $moduleReader;
        $this->filterManager = $filterManager;
    }

    /**
     * @param array $params
     * @return false|Zend_Pdf
     * @throws Zend_Pdf_Exception
     */
    public function getPdf($params = [])
    {
        /** @var $item OrderItem */
        if (empty($params) || !$params[static::PARAM_ITEM] instanceof \Magento\Sales\Model\Order\Item) {
            return false;
        }

        $item = $params[static::PARAM_ITEM];

        $this->_beforeGetPdf();
        $this->_initRenderer(static::RENDERER_TYPE_ITEM);
        $this->_initRenderer(static::RENDERER_TYPE_ITEM_IMAGE);

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        $page = $this->newPage();

        $this->insertLogo($page);

        $this->insertOrder($page, $item->getOrder());

        $this->insertItemHeader($page);

        $this->drawItemByType(static::RENDERER_TYPE_ITEM, $item, $page);

        $this->drawItemByType(static::RENDERER_TYPE_ITEM_IMAGE, $item, $page);

        $this->_afterGetPdf();

        return $pdf;
    }

    protected function drawItemByType(string $type, OrderItem $item, Zend_Pdf_Page $page): void
    {
        $order = $item->getOrder();
        $item->setProductType($type);
        $item->setOrderItem($item);
        $this->_drawItem($item, $page, $order);
    }

    protected function insertItemHeader(Zend_Pdf_Page $page): void
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Products'), 'feed' => 35];

        $lines[0][] = ['text' => __('SKU'), 'feed' => 290, 'align' => 'right'];

        $lines[0][] = ['text' => __('Qty'), 'feed' => 435, 'align' => 'right'];

        $lines[0][] = ['text' => __('Price'), 'feed' => 360, 'align' => 'right'];

        $lines[0][] = ['text' => __('Tax'), 'feed' => 495, 'align' => 'right'];

        $lines[0][] = ['text' => __('Subtotal'), 'feed' => 565, 'align' => 'right'];

        $lineBlock = ['lines' => $lines, 'height' => 5];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }
}