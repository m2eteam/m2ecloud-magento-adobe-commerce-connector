<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\Tax;

use Magento\Framework\ObjectManagerInterface;

class PriceTaxRateFactory
{
    private ObjectManagerInterface $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function createProductPriceTaxRateByOrder(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation
    ): ProductPriceTaxRate {
        return $this->objectManager->create(
            ProductPriceTaxRate::class,
            [
                'taxAmount' => $orderInformation->getTax()->getTotal(),
                'totalPrice' => $orderInformation->getTotals()->getSubtotal(),
                'isEnabledRoundingOfValue' => true,
            ]
        );
    }
}
