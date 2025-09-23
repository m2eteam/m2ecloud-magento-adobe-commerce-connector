<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface InventorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an inventory list.
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface[]
     */
    public function getItems();

    /**
     * Set an inventory list.
     *
     * @param \M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);
}
