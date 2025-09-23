<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory;

abstract class AbstractModel
{
    private \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry;
    private \Magento\Catalog\Model\Product $product;

    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
    }

    public function setProduct(\Magento\Catalog\Model\Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): \Magento\Catalog\Model\Product
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (!isset($this->product)) {
            throw new \Exception('Catalog Product Model is not set');
        }

        return $this->product;
    }

    abstract public function isInStock(): bool;

    /**
     * @return mixed
     */
    abstract public function getQty();

    public function getStockItem(bool $withScope = true): \Magento\CatalogInventory\Api\Data\StockItemInterface
    {
        return $this->stockRegistry->getStockItem(
            $this->getProduct()->getId(),
            $withScope ? $this->getProduct()->getStore()->getWebsiteId() : null
        );
    }
}
