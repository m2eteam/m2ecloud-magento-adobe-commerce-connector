<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface OrderInformationInterface
{
    public const ORDER_ITEMS = 'items';
    public const SHIPPING_ADDRESS = 'shipping_address';
    public const BILLING_ADDRESS = 'billing_address';
    public const STORE_VIEW_CODE = 'store_view_code';
    public const CURRENCY = 'currency';
    public const CHANNEL_ORDER_ID = 'channel_order_id';
    public const SHIPPING_INFORMATION = 'shipping_information';
    public const TAX = 'tax';
    public const TOTALS = 'totals';

    /**
     * Returns order items
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface[]
     */
    public function getOrderItems();

    /**
     * Set order items
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface[] $orderItems
     *
     * @return $this
     */
    public function setOrderItems(array $orderItems);

    /**
     * Returns shipping address
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set shipping address
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address
     *
     * @return $this
     */
    public function setShippingAddress(\M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address);

    /**
     * Returns billing address
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface|null
     */
    public function getBillingAddress();

    /**
     * Set billing address if additional synchronization needed
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address
     *
     * @return $this
     */
    public function setBillingAddress(\M2E\M2ECloudMagentoConnector\Api\Data\AddressInterface $address);

    /**
     * Returns store view code
     * @return string
     */
    public function getStoreViewCode();

    /**
     * Set store view code
     *
     * @param string $storeViewCode
     *
     * @return $this
     */
    public function setStoreViewCode($storeViewCode);

    /**
     * Returns currency
     * @return string
     */
    public function getCurrency();

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Returns channel order id
     * @return string
     */
    public function getChannelOrderId();

    /**
     * Set channel order id
     *
     * @param string $channelOrderId
     *
     * @return $this
     */
    public function setChannelOrderId($channelOrderId);

    /**
     * Set shipping information
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface $shippingInformation
     *
     * @return $this
     */
    public function setShippingInformation(
        \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface $shippingInformation
    );

    /**
     * Returns shipping information
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface|null
     */
    public function getShippingInformation();

    /**
     * Set order tax
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\TaxInterface $tax
     *
     * @return $this
     */
    public function setTax(\M2E\M2ECloudMagentoConnector\Api\Data\TaxInterface $tax);

    /**
     * Returns order tax
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\TaxInterface|null
     */
    public function getTax();

    /**
     * Set total including tax
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals(\M2E\M2ECloudMagentoConnector\Api\Data\TotalsInterface $totals);

    /**
     * Returns total including tax
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\TotalsInterface|null
     */
    public function getTotals();
}
