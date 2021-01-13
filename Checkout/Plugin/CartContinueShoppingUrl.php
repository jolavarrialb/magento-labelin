<?php

declare(strict_types=1);

namespace Labelin\Checkout\Plugin;

use Magento\Checkout\Block\Cart as CheckoutCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Url;

class CartContinueShoppingUrl
{
    protected const HOMEPAGE_SECTION = '#order-section';

    /** @var Session */
    protected $checkoutSession;

    /** @var Url */
    protected $urlBuilder;

    public function __construct(Session $session, Url $urlBuilder)
    {
        $this->checkoutSession = $session;
        $this->urlBuilder = $urlBuilder;
    }

    public function beforeGetContinueShoppingUrl(CheckoutCart $subject): self
    {
        $url = $this->getRedirectUrl();

        $subject->setData('continue_shopping_url', $url);

        return $this;
    }

    protected function getRedirectUrl(): string
    {
        return $this->urlBuilder->getBaseUrl() . static::HOMEPAGE_SECTION;
    }
}
