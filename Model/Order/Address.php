<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class Address extends \Magento\Framework\DataObject implements \M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface
{
    /**
     * @inheritdoc
     */
    public function getCountryId()
    {
        return $this->getData(self::KEY_COUNTRY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::KEY_COUNTRY_ID, $countryId);
    }

    /**
     * @inheritdoc
     */
    public function getStreet()
    {
        $street = $this->getData(self::KEY_STREET) ?? [''];

        return is_array($street) ? $street : explode("\n", $street);
    }

    /**
     * @inheritdoc
     */
    public function setStreet($street)
    {
        return $this->setData(self::KEY_STREET, $street);
    }

    /**
     * @inheritdoc
     */
    public function getCompany()
    {
        return $this->getData(self::KEY_COMPANY);
    }

    /**
     * @inheritdoc
     */
    public function setCompany($company)
    {
        return $this->setData(self::KEY_COMPANY, $company);
    }

    /**
     * @inheritdoc
     */
    public function getTelephone()
    {
        return $this->getData(self::KEY_TELEPHONE);
    }

    /**
     * @inheritdoc
     */
    public function setTelephone($telephone)
    {
        return $this->setData(self::KEY_TELEPHONE, $telephone);
    }

    /**
     * @inheritdoc
     */
    public function getPostcode()
    {
        return $this->getData(self::KEY_POSTCODE);
    }

    /**
     * @inheritdoc
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::KEY_POSTCODE, $postcode);
    }

    /**
     * @inheritdoc
     */
    public function getCity()
    {
        return $this->getData(self::KEY_CITY);
    }

    /**
     * @inheritdoc
     */
    public function setCity($city)
    {
        return $this->setData(self::KEY_CITY, $city);
    }

    /**
     * @inheritdoc
     */
    public function getFirstname()
    {
        return $this->getData(self::KEY_FIRSTNAME);
    }

    /**
     * @inheritdoc
     */
    public function setFirstname($firstname)
    {
        return $this->setData(self::KEY_FIRSTNAME, $firstname);
    }

    /**
     * @inheritdoc
     */
    public function getLastname()
    {
        return $this->getData(self::KEY_LASTNAME);
    }

    /**
     * @inheritdoc
     */
    public function setLastname($lastname)
    {
        return $this->setData(self::KEY_LASTNAME, $lastname);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getData(self::KEY_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        return $this->setData(self::KEY_EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function setRegion($region)
    {
        return $this->setData(self::KEY_REGION, $region);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion()
    {
        return $this->getData(self::KEY_REGION);
    }
}
