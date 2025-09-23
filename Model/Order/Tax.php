<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class Tax extends \Magento\Framework\DataObject implements \M2E\M2ECloudMagentoConnector\Api\Data\TaxInterface
{
    /**
     * @inheritdoc
     */
    public function getTotal()
    {
        return $this->getData(self::KEY_TOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setTotal($total)
    {
        return $this->setData(self::KEY_TOTAL, $total);
    }

    /**
     * @inheritdoc
     */
    public function getShippingTax()
    {
        return $this->getData(self::KEY_SHIPPING_TAX);
    }

    /**
     * @inheritdoc
     */
    public function setShippingTax($shippingTax)
    {
        return $this->setData(self::KEY_SHIPPING_TAX, $shippingTax);
    }
}
