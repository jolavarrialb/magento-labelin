<?php

declare(strict_types=1);

namespace Labelin\Checkout\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Block\Cart as CheckoutCart;
use Magento\Checkout\Model\Session;

class CartContinueShoppingUrl
{
    /** @var Session */
    protected $checkoutSession;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    public function __construct(Session $session, ProductRepositoryInterface $productRepository)
    {
        $this->checkoutSession = $session;
        $this->productRepository = $productRepository;
    }

    public function beforeGetContinueShoppingUrl(CheckoutCart $subject): self
    {
        $url = $subject->getData('continue_shopping_url');

        if ($url === null) {
            $url = $this->checkoutSession->getContinueShoppingUrl(true);

            if (!$url) {
                $url = $this->getRedirectUrl();
            }

            $subject->setData('continue_shopping_url', $url);
        }

        return $this;
    }

    protected function getRedirectUrl(): string
    {
        return $this->productRepository->get('sticker')->getProductUrl();
    }
}
