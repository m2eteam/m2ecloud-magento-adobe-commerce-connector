<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order\Tax;

interface PriceTaxRateInterface
{
    /**
     * @return float|int
     */
    public function getValue();

    /**
     * @return float|int
     */
    public function getNotRoundedValue();

    public function isEnabledRoundingOfValue(): bool;
}
