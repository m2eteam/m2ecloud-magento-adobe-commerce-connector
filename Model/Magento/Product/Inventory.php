<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Product;

use M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\AbstractModel;

class Inventory extends AbstractModel
{
    public function isInStock(): bool
    {
        return (bool)$this->getStockItem()->getIsInStock();
    }

    /**
     * @return float|mixed
     * @throws \Exception
     */
    public function getQty()
    {
        return $this->getStockItem()->getQty();
    }
}
