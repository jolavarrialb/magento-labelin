<?php

declare(strict_types=1);

namespace Labelin\ProductionTicket\Model\Order\Pdf\Renderers;

use Labelin\ProductionTicket\Helper\ProductionTicketImage;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order\Pdf\Items\AbstractItems;
use Magento\Tax\Helper\Data;
use Zend_Pdf_Exception;

class ItemArtwork extends AbstractItems
{
    protected const IMAGE_WIDTH_MAX = 250;

    protected const IMAGE_HEIGHT_MAX = 250;

    /** @var ProductionTicketImage */
    protected $productionTicketHelper;

    public function __construct(
        ProductionTicketImage $productionTicketHelper,
        Context $context,
        Registry $registry,
        Data $taxData,
        Filesystem $filesystem,
        FilterManager $filterManager,
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

        $this->productionTicketHelper = $productionTicketHelper;
    }

    /**
     * @throws FileSystemException
     * @throws LocalizedException
     * @throws Zend_Pdf_Exception
     */
    public function draw(): void
    {
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $imagePath = $this->getImagePath($item);

        $image = \Zend_Pdf_Image::imageWithPath($imagePath);

        $top = $pdf->y;
        //top border of the page
        $widthLimit = static::IMAGE_WIDTH_MAX;
        //half of the page width
        $heightLimit = static::IMAGE_HEIGHT_MAX;
        //assuming the image is not a "skyscraper"
        $width = $image->getPixelWidth();
        $height = $image->getPixelHeight();

        //preserving aspect ratio (proportions)
        $ratio = $width / $height;
        if ($ratio > 1 && $width > $widthLimit) {
            $width = $widthLimit;
            $height = $width / $ratio;
        } elseif ($ratio < 1 && $height > $heightLimit) {
            $height = $heightLimit;
            $width = $height * $ratio;
        } elseif ($ratio == 1 && $height > $heightLimit) {
            $height = $heightLimit;
            $width = $widthLimit;
        }

        $y1 = $top - $height;
        $y2 = $top;
        $x1 = 25;
        $x2 = $x1 + $width;

        //coordinates after transformation are rounded by Zend
        $page->drawImage($image, $x1, $y1, $x2, $y2);

        $pdf->y = $y1 - 10;
    }

    /**
     * @param $item
     * @return string
     * @throws FileSystemException
     */
    protected function getImagePath($item): string
    {
        return $this->productionTicketHelper->getArtworkOptionsPathByItem($item);
    }
}
