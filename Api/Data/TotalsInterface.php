<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface TotalsInterface
{
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_SHIPPING = 'shipping';

    /**
     * Get subtotal
     * @return float
     */
    public function getSubtotal();

    /**
     * Set subtotal
     *
     * @param float $subotal
     *
     * @return $this
     */
    public function setSubtotal($subotal);

    /**
     * Get shipping price
     * @return float
     */
    public function getShipping();

    /**
     * Set order shipping price
     *
     * @param float $shipping
     *
     * @return $this
     */
    public function setShipping($shipping);
}
