<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Helper;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class CacheCleaner extends AbstractHelper
{
    /** @var string[] */
    protected $cacheTypes = [
        'config',
        'layout',
        'block_html',
        'collections',
        'reflection',
        'db_ddl',
        'eav',
        'config_integration',
        'config_integration_api',
        'full_page',
        'translate',
        'config_webservice',
    ];

    /** @var TypeListInterface */
    protected $cacheTypeList;

    /** @var Pool */
    protected $cacheFrontendPool;

    public function __construct(
        Context $context,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        parent::__construct($context);

        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * @param array $cacheTypes
     */
    public function cacheClean($cacheTypes = []): void
    {
        if (!$cacheTypes) {
            $cacheTypes = $this->cacheTypes;
        }

        foreach ($cacheTypes as $type) {
            $this->cacheTypeList->cleanType($type);
        }

        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
