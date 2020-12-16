<?php

namespace Labelin\PitneyBowesShipping\Plugin\Controller\Adminhtml\Order\Shipment;

use Labelin\PitneyBowesShipping\App\Response\FileFactory;
use Magento\Shipping\Controller\Adminhtml\Order\Shipment\PrintLabel as PrintLabelController;

class PrintLabel
{
    /** @var FileFactory */
    protected $fileFactory;

    public function __construct(FileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    /**
     * @param PrintLabelController $subject
     * @param callable $proceed
     *
     * @return mixed
     */
    public function aroundExecute(
        PrintLabelController $subject,
        callable $proceed
    ): mixed {

        $subject->setFileFactory($this->fileFactory);

        return $proceed();
    }
}
