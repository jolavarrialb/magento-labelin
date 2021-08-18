<?php

declare(strict_types=1);

namespace Labelin\Sales\ViewModel\Order;

use Labelin\Sales\Helper\Favourite as FavouriteHelper;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Helper\Reorder as ReorderHelper;

class History implements ArgumentInterface
{
    protected const IS_HISTORY = true;

    /** @var ReorderHelper */
    protected $reorderHelper;

    /** @var PostHelper */
    protected $postHelper;

    /** @var FavouriteHelper */
    protected $favouriteHelper;

    public function __construct(
        ReorderHelper $reorderHelper,
        PostHelper $postHelper,
        FavouriteHelper $favouriteHelper
    ) {
        $this->reorderHelper = $reorderHelper;
        $this->postHelper = $postHelper;
        $this->favouriteHelper = $favouriteHelper;
    }

    public function getReorderHelper(): ReorderHelper
    {
        return $this->reorderHelper;
    }

    public function getPostHelper(): PostHelper
    {
        return $this->postHelper;
    }

    public function getFavouriteHelper(): FavouriteHelper
    {
        return $this->favouriteHelper;
    }

    public function isHistory(): bool
    {
        return static::IS_HISTORY;
    }
}
