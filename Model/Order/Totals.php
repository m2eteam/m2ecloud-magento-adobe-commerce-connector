<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

class Totals extends \Magento\Framework\DataObject implements \M2E\M2ECloudMagentoConnector\Api\Data\TotalsInterface
{
    /**
     * @inheritdoc
     */
    public function getSubtotal()
    {
        return $this->getData(self::KEY_SUBTOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setSubtotal($subotal)
    {
        return $this->setData(self::KEY_SUBTOTAL, $subotal);
    }

    /**
     * @inheritdoc
     */
    public function getShipping()
    {
        return $this->getData(self::KEY_SHIPPING);
    }

    /**
     * @inheritdoc
     */
    public function setShipping($shipping)
    {
        return $this->setData(self::KEY_SHIPPING, $shipping);
    }
}
