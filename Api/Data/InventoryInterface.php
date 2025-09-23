<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface InventoryInterface
{
    public const ID = 'id';
    public const PRODUCT_SKU = 'sku';
    public const PRODUCT_ID = 'product_id';
    public const QTY = 'qty';
    public const IS_IN_STOCK = 'is_in_stock';
    public const IS_MANAGE_STOCK = 'manage_stock';

    /**
     * Product stock id
     * @return int
     */
    public function getId();

    /**
     * Set product stock id
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setId($value);

    /**
     * Product SKU
     * @return string
     */
    public function getProductSku();

    /**
     * Set SKU
     *
     * @param string $sku
     *
     * @return $this
     */
    public function setProductSku($sku);

    /**
     * Product ID
     * @return int
     */
    public function getProductId();

    /**
     * Set product ID
     *
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId);

    /**
     * Product QTY
     * @return int
     */
    public function getQty();

    /**
     * Set product QTY
     *
     * @param int $qty
     *
     * @return $this
     */
    public function setQty($qty);

    /**
     * Product is in stock
     * @return bool
     */
    public function getIsInStock();

    /**
     * Set product is in stock
     *
     * @param bool $isInStock
     *
     * @return $this
     */
    public function setIsInStock($isInStock);

    /**
     * Product is manage stock
     * @return bool
     */
    public function getIsManageStock();

    /**
     * Set product is manage stock
     *
     * @param bool $isManageStock
     *
     * @return $this
     */
    public function setIsManageStock($isManageStock);
}
