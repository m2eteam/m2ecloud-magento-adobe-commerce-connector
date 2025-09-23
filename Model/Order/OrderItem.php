<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class OrderItem extends \Magento\Framework\DataObject implements
    \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::KEY_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::KEY_SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getQty()
    {
        return $this->getData(self::KEY_QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty($qty)
    {
        return $this->setData(self::KEY_QTY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::KEY_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::KEY_PRICE, $price);
    }
}
