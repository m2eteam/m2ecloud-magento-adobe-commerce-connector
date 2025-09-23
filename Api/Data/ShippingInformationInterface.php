<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Api\Data;

interface ShippingInformationInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const KEY_IS_SHIPPED = 'is_shipped';
    public const KEY_TRACKING_TITLE = 'tracking_title';
    public const KEY_TRACKING_NUMBER = 'tracking_number';
    /**#@-*/

    /**
     * Returns is order shipped
     * @return bool
     */
    public function getIsShipped();

    /**
     * Set is order shipped
     *
     * @param bool $isShipped
     *
     * @return $this
     */
    public function setIsShipped($isShipped);

    /**
     * Get tracking title
     * @return string|null
     */
    public function getTrackingTitle();

    /**
     * Set region name
     *
     * @param string|null $trackingTitle
     *
     * @return $this
     */
    public function setTrackingTitle($trackingTitle);

    /**
     * Get tracking number
     * @return string|null
     */
    public function getTrackingNumber();

    /**
     * Set a tracking number
     *
     * @param string|null $trackingNumber
     *
     * @return $this
     */
    public function setTrackingNumber($trackingNumber);
}
