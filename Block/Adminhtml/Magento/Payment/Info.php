<?php

declare(strict_types=1);

namespace M2E\M2ECloudMagentoConnector\Block\Adminhtml\Magento\Payment;

use M2E\M2ECloudMagentoConnector\Model\Magento\Payment as IntegrationPayment;

class Info extends \Magento\Payment\Block\Info
{
    protected $_template = 'M2E_M2ECloudMagentoConnector::magento/order/payment/info.phtml';

    private \Magento\Sales\Model\OrderFactory $orderFactory;
    private ?\Magento\Sales\Model\Order $order = null;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->orderFactory = $orderFactory;
    }

    protected function _toHtml()
    {
        $this->setData('area', \Magento\Framework\App\Area::AREA_ADMINHTML);

        return parent::_toHtml();
    }

    /**
     * @return \Magento\Sales\Model\Order
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOrder(): ?\Magento\Sales\Model\Order
    {
        if ($this->order !== null) {
            return $this->order;
        }

        $orderId = $this->getInfo()->getData('parent_id');
        if (empty($orderId)) {
            return null;
        }

        $this->order = $this->orderFactory->create();
        $this->order->load($orderId);

        return $this->order;
    }

    public function getPaymentMethod(): string
    {
        return (string)$this
            ->getInfo()
            ->getAdditionalInformation(IntegrationPayment::ADDITIONAL_DATA_KEY_PAYMENT_METHOD);
    }

    public function getChannelOrderId(): string
    {
        return (string)$this
            ->getInfo()
            ->getAdditionalInformation(IntegrationPayment::ADDITIONAL_DATA_KEY_CHANNEL_ORDER_ID);
    }
}
