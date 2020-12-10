<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Labelin\PitneyBowesRestApi\Model\Api\Data\AddressDto;

class Address extends AbstractHelper
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        Context $context,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);

        $this->serializer = $serializer;
    }

    public function getAddressDtoModel($data = null): AddressDto
    {
        $result = (new AddressDto());

        if (is_string($data)) {
            $data = $this->serializer->unserialize($data);
        }

        if ($data && is_array($data)) {
            $result
                ->setName($data['name'] ?? '')
                ->setCity($data['city'] ?? '')
                ->setCountry($data['country'] ?? '')
                ->setState($data['state'] ?? '')
                ->setPostcode($data['postcode'] ?? '')
                ->setAddressLines($data['addressLines'] ?? [])
                ->setCompany($data['company'] ?? '')
                ->setEmail($data['email'] ?? '')
                ->setPhone($data['phone'] ?? '');
        }

        return $result;
    }
}
