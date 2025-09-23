<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface TaxInterface
{
    public const KEY_TOTAL = 'total';
    public const KEY_SHIPPING_TAX = 'shipping_tax';

    /**
     * Get order total tax
     * @return float
     */
    public function getTotal();

    /**
     * Set order total tax
     *
     * @param float $total
     *
     * @return $this
     */
    public function setTotal($total);

    /**
     * Get order shipping tax
     * @return float|null
     */
    public function getShippingTax();

    /**
     * Set order shipping tax
     *
     * @param float|null $shippingTax
     *
     * @return $this
     */
    public function setShippingTax($shippingTax);
}
