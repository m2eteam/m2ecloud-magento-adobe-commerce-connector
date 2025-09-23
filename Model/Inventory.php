<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class Inventory extends \Magento\Framework\Model\AbstractModel implements
    \M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface
{
    /**
     * @inheirtDoc
     */
    public function getId()
    {
        return $this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::ID);
    }

    /**
     * @inheirtDoc
     */
    public function setId($value)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::ID, $value);

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getProductSku()
    {
        return $this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::PRODUCT_SKU);
    }

    /**
     * @inheirtDoc
     */
    public function setProductSku($sku)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::PRODUCT_SKU, $sku);

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getQty()
    {
        return $this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::QTY);
    }

    /**
     * @inheirtDoc
     */
    public function setQty($qty)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::QTY, $qty);

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getIsInStock()
    {
        return (bool)$this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::IS_IN_STOCK);
    }

    /**
     * @inheirtDoc
     */
    public function setIsInStock($isInStock)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::IS_IN_STOCK, $isInStock);

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getIsManageStock()
    {
        return (bool)$this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::IS_MANAGE_STOCK);
    }

    /**
     * @inheirtDoc
     */
    public function setIsManageStock($isManageStock)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::IS_MANAGE_STOCK, $isManageStock);

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getProductId()
    {
        return $this->getData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::PRODUCT_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setProductId($productId)
    {
        $this->setData(\M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface::PRODUCT_ID, $productId);

        return $this;
    }
}
