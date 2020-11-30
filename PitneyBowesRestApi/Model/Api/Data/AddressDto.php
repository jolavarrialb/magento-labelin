<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Model\Api\Data;

use Labelin\PitneyBowesRestApi\Api\Data\AddressDtoInterface;

class AddressDto implements AddressDtoInterface
{
    /** @var string */
    protected $company = '';

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $phone = '';

    /** @var string */
    protected $email = '';

    /** @var array */
    protected $addressLines = [];

    /** @var string */
    protected $city = '';

    /** @var string */
    protected $state = '';

    /** @var string */
    protected $postcode = '';

    /** @var string */
    protected $country = '';

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string[] $addressLines
     *
     * @return $this
     */
    public function setAddressLines($addressLines): self
    {
        $this->addressLines = $addressLines;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAddressLines()
    {
        return $this->addressLines;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function toShippingOptionsArray(): array
    {
        return [
            'company' => $this->getCompany(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'residential' => $this->isResidential(),
            'address_lines' => $this->getAddressLines(),
            'city_town' => $this->getCity(),
            'state_province' => $this->getState(),
            'postal_code' => $this->getPostcode(),
            'country_code' => $this->getCountry(),
        ];
    }

    public function toArray(): array
    {
        return [
            'company' => $this->getCompany(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'addressLines' => $this->getAddressLines(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'postcode' => $this->getPostcode(),
            'country' => $this->getCountry(),
        ];
    }

    protected function isResidential(): bool
    {
        return true;
    }
}
