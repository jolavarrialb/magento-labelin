<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api\Data;

interface AddressDtoInterface
{
    /**
     * @param string $company
     *
     * @return $this
     */
    public function setCompany(string $company);

    /**
     * @return string
     */
    public function getCompany(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone);

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email);

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
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city);

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState(string $state);

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode(string $postcode);

    /**
     * @return string
     */
    public function getPostcode(): string;

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry(string $country);

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
