<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

use M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface;

class OrderInformation extends \Magento\Framework\DataObject implements OrderInformationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        return $this->getData(self::SHIPPING_ADDRESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(\M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address)
    {
        return $this->setData(self::SHIPPING_ADDRESS, $address);
    }

    /**
     * {@inheritdoc}
     */
    public function getBillingAddress()
    {
        return $this->getData(self::BILLING_ADDRESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddress(\M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address)
    {
        return $this->setData(self::BILLING_ADDRESS, $address);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderItems()
    {
        return $this->getData(self::ORDER_ITEMS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderItems(array $orderItems)
    {
        return $this->setData(self::ORDER_ITEMS, $orderItems);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreViewCode()
    {
        return $this->getData(self::STORE_VIEW_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreViewCode($storeViewCode)
    {
        return $this->setData(self::STORE_VIEW_CODE, $storeViewCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->getData(self::CURRENCY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelOrderId()
    {
        return $this->getData(self::CHANNEL_ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setChannelOrderId($channelOrderId)
    {
        return $this->setData(self::CHANNEL_ORDER_ID, $channelOrderId);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingInformation(
        \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface $shippingInformation
    ) {
        return $this->setData(self::SHIPPING_INFORMATION, $shippingInformation);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingInformation()
    {
        return $this->getData(self::SHIPPING_INFORMATION);
    }

    /**
     * {@inheritdoc}
     */
    public function setTax(\M2E\M2ECloudMagentoConnector\Api\Data\TaxInterface $tax)
    {
        return $this->setData(self::TAX, $tax);
    }

    /**
     * {@inheritdoc}
     */
    public function getTax()
    {
        return $this->getData(self::TAX);
    }

    /**
     * {@inheritdoc}
     */
    public function setTotals(\M2E\M2ECloudMagentoConnector\Api\Data\TotalsInterface $totals)
    {
        return $this->setData(self::TOTALS, $totals);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotals()
    {
        return $this->getData(self::TOTALS);
    }
}
