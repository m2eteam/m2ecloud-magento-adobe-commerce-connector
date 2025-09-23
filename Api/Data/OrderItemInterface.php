<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface OrderItemInterface
{
    public const KEY_SKU = 'sku';
    public const KEY_QTY = 'qty';
    public const KEY_PRICE = 'price';

    /**
     * Returns the product SKU.
     * @return string|null Product SKU. Otherwise, null.
     */
    public function getSku();

    /**
     * Sets the product SKU.
     *
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku);

    /**
     * Returns the product quantity.
     * @return float Product quantity.
     */
    public function getQty();

    /**
     * Sets the product quantity.
     *
     * @param float $qty
     *
     * @return $this
     */
    public function setQty($qty);

    /**
     * Returns the product price.
     * @return float|null Product price. Otherwise, null.
     */
    public function getPrice();

    /**
     * Sets the product price.
     *
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price);
}
