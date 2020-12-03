<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface AddressDtoInterface
{
    /**
     * @param string|null $company
     *
     * @return $this
     */
    public function setCompany(?string $company);

    /**
     * @return string
     */
    public function getCompany(): string;

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone);

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @param string|null $email
     *
     * @return $this
     */
    public function setEmail(?string $email);

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string[] $addressLines
     *
     * @return $this
     */
    public function setAddressLines($addressLines);

    /**
     * @return string[]
     */
    public function getAddressLines();

    /**
     * @param string|null $city
     *
     * @return $this
     */
    public function setCity(?string $city);

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @param string|null $state
     *
     * @return $this
     */
    public function setState(?string $state);

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @param string|null $postcode
     *
     * @return $this
     */
    public function setPostcode(?string $postcode);

    /**
     * @return string
     */
    public function getPostcode(): string;

    /**
     * @param string|null $country
     *
     * @return $this
     */
    public function setCountry(?string $country);

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return array
     */
    public function toShippingOptionsArray(): array;

    /**
     * @return array
     */
    public function toArray(): array;
}
