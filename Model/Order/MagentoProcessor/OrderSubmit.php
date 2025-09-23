<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\MagentoProcessor;

use M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface;
use M2E\M2ECloudMagentoConnector\Model\Magento;
use Magento\Sales\Api\Data\OrderInterface;

class OrderSubmit
{
    private Magento\Quote\BuilderFactory $quoteBuilderFactory;
    private Magento\Quote\Manager $quoteManager;
    private \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObjectFactory $proxyObjectFactory;
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\BuilderFactory $quoteBuilderFactory,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Manager $quoteManager,
        \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObjectFactory $proxyObjectFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->quoteBuilderFactory = $quoteBuilderFactory;
        $this->quoteManager = $quoteManager;
        $this->proxyObjectFactory = $proxyObjectFactory;
        $this->storeManager = $storeManager;
    }

    public function process(OrderInformationInterface $orderInformation): OrderInterface
    {
        $proxyOrder = $this->getProxy($orderInformation);
        $magentoQuoteBuilder = $this->quoteBuilderFactory->create($proxyOrder);
        $magentoQuote = $magentoQuoteBuilder->build();

        return $this->quoteManager->submit($magentoQuote);
    }

    private function getProxy(
        OrderInformationInterface $orderInformation
    ): \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject {
        $proxyOrder = $this->proxyObjectFactory->create($orderInformation);
        $store = $this->storeManager->getStore($orderInformation->getStoreViewCode());
        $proxyOrder->setStore($store);

        return $proxyOrder;
    }
}
