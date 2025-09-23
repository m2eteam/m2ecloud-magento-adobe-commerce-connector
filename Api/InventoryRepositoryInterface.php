<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api;

interface InventoryRepositoryInterface
{
    /**
     * Returns an inventory list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\InventorySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Returns an inventory for a single product
     *
     * @param string $sku
     *
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\InventoryInterface
     */
    public function get(string $sku);
}
