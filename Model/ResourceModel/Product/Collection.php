<?php

namespace M2E\M2ECloudMagentoConnector\Model\ResourceModel\Product;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    private \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $connection
        );

        $this->stockConfiguration = $stockConfiguration;
    }

    public function joinStockItem(): self
    {
        if ($this->getStoreId() === null) {
            throw new \Exception('Store view was not set.');
        }

        $this->joinTable(
            ['cisi' => $this->getTable('cataloginventory_stock_item')],
            'product_id=entity_id',
            ['is_manage_stock' => $this->getIsManageStockExpression()],
            [
                'stock_id' => \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID,
                'website_id' => $this->stockConfiguration->getDefaultScopeId(),
            ],
            'left'
        );

        return $this;
    }

    private function getIsManageStockExpression(): \Zend_Db_Expr
    {
        $isManageStock = $this->stockConfiguration->getManageStock($this->getStoreId());

        return new \Zend_Db_Expr(
            'IF(cisi.use_config_manage_stock = 1, ' . $isManageStock . ', cisi.manage_stock)'
        );
    }
}
