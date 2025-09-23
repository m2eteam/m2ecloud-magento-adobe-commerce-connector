<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Model\Order;

use M2E\M2ECloudMagentoConnector\Api\Data\ShippingInformationInterface;

class ShippingInformation extends \Magento\Framework\DataObject implements ShippingInformationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getIsShipped()
    {
        return $this->getData(self::KEY_IS_SHIPPED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsShipped($isShipped)
    {
        return $this->setData(self::KEY_IS_SHIPPED, $isShipped);
    }

    /**
     * {@inheritdoc}
     */
    public function getTrackingTitle()
    {
        return $this->getData(self::KEY_TRACKING_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTrackingTitle($trackingTitle)
    {
        return $this->setData(self::KEY_TRACKING_TITLE, $trackingTitle);
    }

    /**
     * {@inheritdoc}
     */
    public function getTrackingNumber()
    {
        return $this->getData(self::KEY_TRACKING_NUMBER);
    }

    /**
     * {@inheritdoc}
     */
    public function setTrackingNumber($trackingNumber)
    {
        return $this->setData(self::KEY_TRACKING_NUMBER, $trackingNumber);
    }
}
