<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface AddressInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const KEY_EMAIL = 'email';
    public const KEY_COUNTRY_ID = 'country_id';
    public const KEY_REGION = 'region';
    public const KEY_STREET = 'street';
    public const KEY_COMPANY = 'company';
    public const KEY_TELEPHONE = 'telephone';
    public const KEY_POSTCODE = 'postcode';
    public const KEY_CITY = 'city';
    public const KEY_FIRSTNAME = 'firstname';
    public const KEY_LASTNAME = 'lastname';
    /**#@-*/

    /**
     * Get region name
     * @return string
     */
    public function getRegion();

    /**
     * Set region name
     *
     * @param string $region
     *
     * @return $this
     */
    public function setRegion($region);

    /**
     * Get country id
     * @return string
     */
    public function getCountryId();

    /**
     * Set country id
     *
     * @param string $countryId
     *
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * Get street
     * @return string[]
     */
    public function getStreet();

    /**
     * Set street
     *
     * @param string|string[] $street
     *
     * @return $this
     */
    public function setStreet($street);

    /**
     * Get telephone number
     * @return string
     */
    public function getTelephone();

    /**
     * Set telephone number
     *
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Get postcode
     * @return string
     */
    public function getPostcode();

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Get city name
     * @return string
     */
    public function getCity();

    /**
     * Set city name
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city);

    /**
     * Get first name
     * @return string
     */
    public function getFirstname();

    /**
     * Set first name
     *
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname($firstname);

    /**
     * Get last name
     * @return string
     */
    public function getLastname();

    /**
     * Set last name
     *
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname($lastname);

    /**
     * Get billing/shipping email
     * @return string
     */
    public function getEmail();

    /**
     * Set billing/shipping email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);
}
