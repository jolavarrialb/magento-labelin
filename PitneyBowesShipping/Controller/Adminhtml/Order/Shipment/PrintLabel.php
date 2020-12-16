<?php

namespace Labelin\PitneyBowesShipping\Controller\Adminhtml\Order\Shipment;

use Magento\Framework\App\Response\Http\FileFactory;

class PrintLabel extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\PrintLabel
{
    /**
     * @param FileFactory $fileFactory
     *
     * @return $this
     */
    public function setFileFactory(FileFactory $fileFactory): self
    {
        $this->_fileFactory = $fileFactory;

        return $this;
    }
}
