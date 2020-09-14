<?php

declare(strict_types=1);

namespace Labelin\Sales\Controller\Download;

use Labelin\Sales\Helper\Artwork;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Download;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class DownloadCustomOptionFile extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /** @var ForwardFactory */
    protected $resultForwardFactory;

    /** @var Download */
    protected $download;

    /** @var Json */
    private $serializer;

    /** @var OrderItemRepositoryInterface */
    protected $itemRepository;

    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        Download $download,
        OrderItemRepositoryInterface $itemRepository,
        Json $serializer = null
    )
    {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->download = $download;
        $this->itemRepository = $itemRepository;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(
            Json::class
        );
    }

    /**
     * Custom options download file action
     *
     * @return void|\Magento\Framework\Controller\Result\Forward
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();

        $quoteItemOptionId = $this->getRequest()->getParam('id');

        if (empty($quoteItemOptionId)) {
            return $resultForward->forward('noroute');
        }

        $productCustomOption = $this->getOption($quoteItemOptionId);

        if (empty($productCustomOption)) {
            return $resultForward->forward('noroute');
        }

        try {
            $info = $this->serializer->unserialize($productCustomOption['option_value']);

            if ($this->getRequest()->getParam('key') !== $info['secret_key']) {
                return $resultForward->forward('noroute');
            }

            $this->download->downloadFile($info);
        } catch (\Exception $e) {
            return $resultForward->forward('noroute');
        }
        $this->endExecute();
    }

    protected function endExecute(): void
    {
        // phpcs:ignore Magento2.Security.LanguageConstruct.ExitUsage
        exit(0);
    }

    protected function getOption($itemId): array
    {
        $item = $this->itemRepository->get($itemId);
        $productOptions = $item->getProductOptions();

        if (!is_array($productOptions) || !isset($productOptions['options'])) {

            return [];
        }

        foreach ($productOptions['options'] as $productOption) {
            if (array_key_exists('option_type', $productOption) && $productOption['option_type'] === Artwork::FILE_OPTION_TYPE) {
                return $productOption;
            }
        }

        return [];
    }
}

