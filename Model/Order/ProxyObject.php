<?php

namespace M2E\M2ECloudMagentoConnector\Model\Order;

use M2E\M2ECloudMagentoConnector\Model\Magento\Payment as M2ePayment;
use M2E\M2ECloudMagentoConnector\Model\Order\Tax\PriceTaxRateInterface;

class ProxyObject
{
    public const CHECKOUT_GUEST = 'guest';

    private \M2E\M2ECloudMagentoConnector\Model\Currency $currency;
    private M2ePayment $payment;
    private \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation;
    private \Magento\Store\Model\Store $store;
    private array $shippingAddressData = [];
    private array $billingAddressData = [];
    private \M2E\M2ECloudMagentoConnector\Model\Order\Tax\PriceTaxRateFactory $priceTaxRateFactory;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Shipping $shipping;
    private ProxyObject\RegionResolver $regionResolver;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderInformationInterface $orderInformation,
        \M2E\M2ECloudMagentoConnector\Model\Currency $currency,
        M2ePayment $payment,
        \M2E\M2ECloudMagentoConnector\Model\Order\Tax\PriceTaxRateFactory $priceTaxRateFactory,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Shipping $shipping,
        \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject\RegionResolver $regionResolver
    ) {
        $this->orderInformation = $orderInformation;
        $this->currency = $currency;
        $this->payment = $payment;
        $this->priceTaxRateFactory = $priceTaxRateFactory;
        $this->shipping = $shipping;
        $this->regionResolver = $regionResolver;
    }

    public function getPaymentMethod(): string
    {
        return $this->payment->getCode();
    }

    public function getCarrierCode(): string
    {
        return $this->shipping->getCarrierCode();
    }

    public function getShippingMethodCode(): string
    {
        return $this->shipping->getShippingMethodCode();
    }

    /**
     * @return \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface[]
     */
    public function getItems(): array
    {
        return $this->orderInformation->getOrderItems();
    }

    /**
     * @param \Magento\Store\Model\Store $store
     *
     * @return $this
     */
    public function setStore(\Magento\Store\Model\Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @return \Magento\Store\Model\Store
     * @throws \Exception
     */
    public function getStore(): \Magento\Store\Model\Store
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (!isset($this->store)) {
            throw new \Exception('Store is not set.');
        }

        /** @psalm-suppress NoValue */
        return $this->store;
    }

    public function getCheckoutMethod(): string
    {
        return self::CHECKOUT_GUEST;
    }

    public function isCheckoutMethodGuest(): bool
    {
        return $this->getCheckoutMethod() == self::CHECKOUT_GUEST;
    }

    public function getChannelOrderNumber(): string
    {
        return $this->orderInformation->getChannelOrderId();
    }

    public function getCustomerFirstName()
    {
        return $this->orderInformation->getShippingAddress()->getFirstname();
    }

    public function getCustomerLastName()
    {
        return $this->orderInformation->getShippingAddress()->getLastname();
    }

    public function getBuyerEmail()
    {
        return $this->orderInformation->getShippingAddress()->getEmail();
    }

    /**
     * @return array
     */
    public function getShippingAddress(): array
    {
        if (empty($this->shippingAddressData)) {
            $shippingAddress = $this->orderInformation->getShippingAddress();
            $this->shippingAddressData['firstname'] = $shippingAddress->getFirstname();
            $this->shippingAddressData['lastname'] = $shippingAddress->getLastname();
            $this->shippingAddressData['email'] = $shippingAddress->getEmail();
            $this->shippingAddressData['country_id'] = $shippingAddress->getCountryId();
            $this->shippingAddressData['region'] = $shippingAddress->getRegion();
            $this->shippingAddressData['region_id'] = $this->regionResolver->getRegionIdByName(
                $shippingAddress->getCountryId(),
                $shippingAddress->getRegion()
            );
            $this->shippingAddressData['city'] = $shippingAddress->getCity();
            $this->shippingAddressData['postcode'] = $shippingAddress->getPostcode();
            $this->shippingAddressData['telephone'] = $shippingAddress->getTelephone();
            $this->shippingAddressData['street'] = $shippingAddress->getStreet();
            $this->shippingAddressData['save_in_address_book'] = 0;
        }

        return $this->shippingAddressData;
    }

    public function getBillingAddressData(): array
    {
        if (empty($this->billingAddressData)) {
            $billingAddress = $this->orderInformation->getShippingAddress();
            $this->billingAddressData['firstname'] = $billingAddress->getFirstname();
            $this->billingAddressData['lastname'] = $billingAddress->getLastname();
            $this->billingAddressData['email'] = $billingAddress->getEmail();
            $this->billingAddressData['country_id'] = $billingAddress->getCountryId();
            $this->billingAddressData['region'] = $billingAddress->getRegion();
            $this->billingAddressData['region_id'] = $this->regionResolver->getRegionIdByName(
                $billingAddress->getCountryId(),
                $billingAddress->getRegion()
            );
            $this->billingAddressData['city'] = $billingAddress->getCity();
            $this->billingAddressData['postcode'] = $billingAddress->getPostcode();
            $this->billingAddressData['telephone'] = $billingAddress->getTelephone();
            $this->billingAddressData['street'] = $billingAddress->getStreet();
            $this->billingAddressData['save_in_address_book'] = 0;
        }

        return $this->billingAddressData;
    }

    public function shouldIgnoreBillingAddressValidation(): bool
    {
        return false;
    }

    public function getCurrency(): string
    {
        return $this->orderInformation->getCurrency();
    }

    public function convertPrice($price)
    {
        return $this->currency->convertPrice($price, $this->getCurrency(), $this->getStore());
    }

    public function convertPriceToBase($price)
    {
        return $this->currency->convertPriceToBaseCurrency($price, $this->getCurrency(), $this->getStore());
    }

    public function getPaymentData(): array
    {
        return [
            \Magento\Quote\Api\Data\PaymentInterface::KEY_METHOD => $this->payment->getCode(),
            \Magento\Quote\Api\Data\PaymentInterface::KEY_ADDITIONAL_DATA => [
                'payment_method' => '',
                'channel_order_id' => $this->orderInformation->getChannelOrderId(),
            ],
        ];
    }

    public function getShippingData(): array
    {
        return [
            'carrier_title' => (string)__(
                '%channel_title Delivery Option',
                [
                    'channel_title' => 'M2E Cloud Connector',
                ],
            ),
            'shipping_method' => $this->shipping->getShippingMethodCode(),
            'shipping_price' => $this->getBaseShippingPrice(),
        ];
    }

    private function getBaseShippingPrice()
    {
        return $this->convertPriceToBase($this->getShippingPrice());
    }

    /**
     * @return float
     */
    private function getShippingPrice()
    {
        return $this->orderInformation->getTotals()->getShipping();
    }

    public function hasTax(): bool
    {
        return $this->getTaxRate() > 0;
    }

    /**
     * @return int|float
     */
    public function getTaxRate()
    {
        return $this->priceTaxRateFactory->createProductPriceTaxRateByOrder($this->orderInformation)->getValue();
    }

    // ---------------------------------------

    /**
     * @return float|int
     */
    public function getProductPriceTaxRate()
    {
        if (!$this->hasTax()) {
            return 0;
        }

        return $this->getTaxRate();
    }

    public function getProductPriceTaxRateObject(): PriceTaxRateInterface
    {
        return $this->priceTaxRateFactory->createProductPriceTaxRateByOrder($this->orderInformation);
    }

    /**
     * @return float|int
     */
    public function getShippingPriceTaxRate()
    {
        if (!$this->hasTax()) {
            return 0;
        }

        if (!$this->orderInformation->getTax()->getShippingTax()) {
            return 0;
        }

        return $this->getProductPriceTaxRate();
    }

    public function getShippingPriceTaxRateObject(): ?PriceTaxRateInterface
    {
        return null;
    }

    // ---------------------------------------

    public function isProductPriceIncludeTax(): ?bool
    {
        return false;
    }

    public function isShippingPriceIncludeTax(): ?bool
    {
        //Taxes from chanel
        return true;
    }

    public function isTaxModeNone(): bool
    {
        return false;
    }

    public function isTaxModeChannel(): bool
    {
        return true;
    }

    public function isTaxModeMagento(): bool
    {
        return false;
    }

    public function isTaxModeMixed(): bool
    {
        return false;
    }

    public function getComments(): array
    {
        return array_merge($this->getGeneralComments(), $this->getChannelComments());
    }

    /**
     * @return array
     */
    public function getChannelComments()
    {
        return [];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getGeneralComments()
    {
        $store = $this->getStore();
        $currencyConvertRate = $this->currency->getConvertRateFromBase($this->getCurrency(), $store, 4);

        if ($this->currency->isBase($this->getCurrency(), $store)) {
            return [];
        }

        $comments = [];

        if (!$this->currency->isAllowed($this->getCurrency(), $store)) {
            $comments[] = (string)__(
                '<b>Attention!</b> The Order Prices are incorrect. Conversion was not ' .
                'performed as "%order_currency" Currency is not enabled. Default ' .
                'Currency "%store_currency" was used instead. Please, ' .
                'enable Currency in System > Configuration > Currency Setup.',
                [
                    'order_currency' => $this->getCurrency(),
                    'store_currency' => $store->getBaseCurrencyCode(),
                ]
            );
        } elseif ($currencyConvertRate == 0) {
            $comments[] = __(
                '<b>Attention!</b> The Order Prices are incorrect. Conversion was not ' .
                'performed as there\'s no rate for "%order_currency". Default Currency ' .
                '"%store_currency" was used instead. Please, add Currency convert ' .
                'rate in System > Manage Currency > Rates.',
                [
                    'order_currency' => $this->getCurrency(),
                    'store_currency' => $store->getBaseCurrencyCode(),
                ]
            );
        } else {
            $comments[] = __(
                'Because the Order Currency is different from the Store Currency, the conversion ' .
                'from <b>"%order_currency" to "%store_currency"</b> was performed ' .
                'using <b>%currency_rate</b> as a rate.',
                [
                    'order_currency' => $this->getCurrency(),
                    'store_currency' => $store->getBaseCurrencyCode(),
                    'currency_rate' => $currencyConvertRate,
                ]
            );
        }

        return $comments;
    }
}
