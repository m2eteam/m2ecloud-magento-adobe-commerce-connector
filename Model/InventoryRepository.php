<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model;

class InventoryRepository implements \M2E\M2ECloudMagentoConnector\Api\InventoryRepositoryInterface
{
    private \M2E\M2ECloudMagentoConnector\Model\InventorySearchResultsFactory $searchResultsFactory;
    private \M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product\CollectionFactory $collectionFactory;
    private \M2E\M2ECloudMagentoConnector\Model\InventoryFactory $inventoryFactory;
    private \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\Factory $magentoInventoryFactory;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\InventorySearchResultsFactory $searchResultsFactory,
        \M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \M2E\M2ECloudMagentoConnector\Model\InventoryFactory $inventoryFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Product\Inventory\Factory $magentoInventoryFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->inventoryFactory = $inventoryFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->magentoInventoryFactory = $magentoInventoryFactory;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->createInventoryCollection();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $result = [];
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($collection->getItems() as $item) {
            $result[] = $this->processInventoryItem($item);
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($result);
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheirtDoc
     */
    public function get(string $sku)
    {
        $collection = $this->createInventoryCollection();
        $collection->addFieldToFilter('sku', $sku);

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $collection->getFirstItem();
        if (!$product->getEntityId()) {
            throw new \Exception((string)__('Product with SKU "%sku" not found.', ['sku' => $sku]));
        }

        return $this->processInventoryItem($product);
    }

    private function createInventoryCollection(): \M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product\Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->joinStockItem();
        $collection->addFieldToFilter('type_id', 'simple');

        return $collection;
    }

    private function processInventoryItem(
        \Magento\Catalog\Model\Product $item
    ): \M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface {
        $magentoInventory = $this->magentoInventoryFactory->getObject($item);
        $inventory = $this->inventoryFactory->create();
        /** @psalm-suppress UndefinedInterfaceMethod */
        $inventory->setId($magentoInventory->getStockItem()->getItemId())
                  ->setQty($magentoInventory->getQty())
                  ->setProductSku($magentoInventory->getProduct()->getSku())
                  ->setIsInStock($magentoInventory->isInStock())
                  ->setIsManageStock((bool)$item->getData('is_manage_stock'))
                  ->setProductId($magentoInventory->getProduct()->getId());

        return $inventory;
    }
}
