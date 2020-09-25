<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\Block\Product\Renderer;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceInfo\Base;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Swatches\Block\Product\Renderer\Configurable as MagentoSwatchesConfigurable;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Locale\Format;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\SwatchAttributesProvider;

class Configurable extends MagentoSwatchesConfigurable
{
    protected const LABELIN_SWATCH_RENDERER_TEMPLATE = 'Labelin_ConfigurableProduct::product/view/renderer.phtml';

    protected const LIMIT_COLLAPSE_QUANTITY_FOR_TIER_PRICES = 4;

    /** @var Format|mixed|null */
    protected $localeFormat;

    /** @var Prices|mixed|null */
    protected $variationPrices;

    /** @var EncoderInterface */
    protected $encoderJson;

    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        CatalogProduct $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData $swatchHelper,
        Media $swatchMediaHelper,
        array $data = [],
        SwatchAttributesProvider $swatchAttributesProvider = null,
        UrlBuilder $imageUrlBuilder = null,
        Format $localeFormat = null,
        Prices $variationPrices = null
    ) {

        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $swatchHelper,
            $swatchMediaHelper,
            $data,
            $swatchAttributesProvider,
            $imageUrlBuilder
        );
        $this->localeFormat = $localeFormat ?: ObjectManager::getInstance()->get(Format::class);
        $this->variationPrices = $this->variationPrices = $variationPrices ?: ObjectManager::getInstance()->get(
            Prices::class
        );
        $this->encoderJson = $jsonEncoder;
    }

    public function getOptionSizeHeader(): string
    {
        return $this->encoderJson->encode(
            [
                'header' => "Set Size<span>/inch</span>",
                'headerInfo' => "Note that size is: width by height (WxH)",
            ]
        );
    }

    public function getOptionShapeHeader(): string
    {
        return $this->encoderJson->encode(
            [
                'header' => "Choose Shape",
                'headerInfo' => "Order will take 8-10 business days",
            ]
        );
    }

    public function getOptionTypeHeader(): string
    {
        return $this->encoderJson->encode(
            [
                'header' => "Pick Type",
                'headerInfo' => "Click info icon to see materials characteristics",
            ]
        );
    }

    public function getSizeBlockImgUrl(): string
    {
        return '';
    }

    protected function getOptionPrices(): array
    {
        $prices = [];
        foreach ($this->getAllowProducts() as $product) {

            $priceInfo = $product->getPriceInfo();
            $prices[$product->getId()] =
                [
                    'oldPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('regular_price')->getAmount()->getValue()
                        ),
                    ],
                    'basePrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('final_price')->getAmount()->getBaseAmount()
                        ),
                    ],
                    'finalPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('final_price')->getAmount()->getValue()
                        ),
                    ],
                    'tierPrices' => $this->prepareProductTierPrices($priceInfo),
                    'msrpPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $product->getMsrp()
                        ),
                    ],
                ];
        }

        return $prices;
    }

    protected function prepareProductTierPrices(Base $priceInfo): array
    {
        $tierPriceModel = $priceInfo->getPrice('tier_price');
        $tierPricesList = $tierPriceModel->getTierPriceList();

        if (empty($tierPricesList)) {
            return [];
        }

        $tierPrices = [];
        $tierPricesListQty = count($tierPricesList);
        $pricesQtyForSubGroup = (int)ceil($tierPricesListQty / static::LIMIT_COLLAPSE_QUANTITY_FOR_TIER_PRICES);
        $count = 0;
        $tierPricesListCount = 0;
        $groupPrices = [];
        $minGroupQty = 0;

        foreach ($tierPricesList as $tierPrice) {
            $tierPricesListCount++;

            if ($count === 0) {
                $minGroupQty = $this->localeFormat->getNumber($tierPrice['price_qty']);
            }

            if ($count === $pricesQtyForSubGroup - 1 || $tierPricesListCount === $tierPricesListQty) {
                $maxGroupQty = $this->localeFormat->getNumber($tierPrice['price_qty']);
                $groupPrices[] = [
                    'qty' => $this->localeFormat->getNumber($tierPrice['price_qty']),
                    'price' => $this->localeFormat->getNumber($tierPrice['price']->getValue()),
                    'bulkPrice' => $this->localeFormat->getNumber($tierPrice['price']->getValue() * $tierPrice['price_qty']),
                    'percentage' => $this->localeFormat->getNumber(
                        $tierPriceModel->getSavePercent($tierPrice['price'])
                    ),
                ];
                $priceGroupLabel = sprintf('%d - %d', $minGroupQty, $maxGroupQty);
                $tierPrices[] = [
                    'label' => $priceGroupLabel,
                    'groupData' => $groupPrices,
                ];
                $count = 0;
                $groupPrices = [];
                continue;
            }

            $groupPrices[] = [
                'qty' => $this->localeFormat->getNumber($tierPrice['price_qty']),
                'price' => $this->localeFormat->getNumber($tierPrice['price']->getValue()),
                'bulkPrice' => $this->localeFormat->getNumber($tierPrice['price']->getValue() * $tierPrice['price_qty']),
                'percentage' => $this->localeFormat->getNumber(
                    $tierPriceModel->getSavePercent($tierPrice['price'])
                ),
            ];
            $count++;

        }

        return $tierPrices;
    }

    protected function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute() ?
            self::LABELIN_SWATCH_RENDERER_TEMPLATE : self::CONFIGURABLE_RENDERER_TEMPLATE;
    }
}
