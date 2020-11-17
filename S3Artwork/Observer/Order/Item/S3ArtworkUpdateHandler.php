<?php

declare(strict_types=1);

namespace Labelin\S3Artwork\Observer\Order\Item;

use Labelin\S3Artwork\Helper\S3Artwork;
use Labelin\Sales\Model\Order\Item;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class S3ArtworkUpdateHandler implements ObserverInterface
{
    /** @var S3Artwork */
    protected $s3ArtworkHelper;

    public function __construct(S3Artwork $s3ArtworkHelper)
    {
        $this->s3ArtworkHelper = $s3ArtworkHelper;
    }

    public function execute(Observer $observer)
    {
        /** @var Item $item */
        $item = $observer->getData('item');

        if (!$item) {
            return $this;
        }

        $this->s3ArtworkHelper->saveArtworkToS3Folder($item);

        return $this;
    }
}
