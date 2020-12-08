<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Helper;

use Labelin\PitneyBowesOfficialApi\Model\Api\Model\SpecialService;
use Labelin\PitneyBowesRestApi\Api\Data\ShipmentsRatesDtoInterface;
use Labelin\PitneyBowesRestApi\Api\Data\SpecialServiceDtoInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Rates extends AbstractHelper
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function getAllSpecialServices(ShipmentsRatesDtoInterface $rates): array
    {
        $result = [];
        $specialService = $rates->getSpecialService();

        if ($specialService) {
            /** @var SpecialServiceDtoInterface $service */
            foreach ($specialService as $service) {
                $result[] = new SpecialService([
                    'special_service_id' => $service->getSpecialServiceId(),
                    'input_parameters' => current($service->getInputParameters()),
                ]);
            }
        }

        return $result;
    }
}

