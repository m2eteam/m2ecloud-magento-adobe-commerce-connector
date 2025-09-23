<?php

namespace M2E\M2ECloudMagentoConnector\Model\MSI\Magento\Product;

use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\InventoryIndexer\Model\ResourceModel\GetStockItemData;
use Magento\InventoryReservations\Model\ResourceModel\GetReservationsQuantity;

class Inventory extends \M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\AbstractModel
{
    private GetStockItemData $getStockItemData;
    private GetProductSalableQtyInterface $salableQtyResolver;
    private StockResolverInterface $stockResolver;
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($stockRegistry);

        $this->getStockItemData = $objectManager->get(GetStockItemData::class);
        $this->salableQtyResolver = $objectManager->create(
            GetProductSalableQtyInterface::class,
            [
                'getStockItemData' => $this->getStockItemData,
                'getReservationsQuantity' => $objectManager->get(GetReservationsQuantity::class),
            ]
        );
        $this->stockResolver = $objectManager->get(StockResolverInterface::class);
        $this->storeManager = $storeManager;
    }

    public function isInStock(): bool
    {
        $stockItemData = $this->getStockItemData->execute(
            $this->getProduct()->getSku(),
            $this->getStock()->getStockId()
        );
        $result = $stockItemData === null ? 0 : $stockItemData[GetStockItemData::IS_SALABLE];

        return (bool)$result;
    }

    /**
     * @return float|int|mixed
     * @throws \Exception
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQty()
    {
        try {
            $qty = $this->salableQtyResolver->execute($this->getProduct()->getSku(), $this->getStock()->getId());
        } catch (\Magento\InventoryConfigurationApi\Exception\SkuIsNotAssignedToStockException $exception) {
            $qty = 0;
        }

        return $qty;
    }

    /**
     * @return \Magento\InventoryApi\Api\Data\StockInterface
     * @throws \Exception
     */
    private function getStock()
    {
        $website = $this->getProduct()->getStoreId() === 0
            ? $this->storeManager->getWebsite(true)
            : $this->getProduct()->getStore()->getWebsite();

        return $this->stockResolver->execute(SalesChannelInterface::TYPE_WEBSITE, $website->getCode());
    }
}
