<?php

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Quote;

use M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface;
use Magento\Framework\Exception\LocalizedException;

class Builder
{
    public const PROCESS_QUOTE_ID = 'PROCESS_QUOTE_ID';

    private \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject $proxyOrder;
    private \Magento\Quote\Model\Quote $quote;
    private \M2E\M2ECloudMagentoConnector\Model\Currency $currency;
    private \Magento\Directory\Model\CurrencyFactory $magentoCurrencyFactory;
    private \Magento\Tax\Model\Calculation $calculation;
    private \Magento\Framework\App\Config\ReinitableConfigInterface $storeConfig;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Manager $quoteManager;
    private ?\M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Store\Configurator $storeConfigurator = null;
    private \Magento\Sales\Model\OrderIncrementIdChecker $orderIncrementIdChecker;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Store\ConfiguratorFactory $configuratorFactory;
    private \M2E\M2ECloudMagentoConnector\Helper\Data\GlobalData $globalDataHelper;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\ItemFactory $magentoQuoteItemFactory;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject $proxyOrder,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Store\ConfiguratorFactory $configuratorFactory,
        \M2E\M2ECloudMagentoConnector\Model\Currency $currency,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Manager $quoteManager,
        \Magento\Directory\Model\CurrencyFactory $magentoCurrencyFactory,
        \Magento\Tax\Model\Calculation $calculation,
        \Magento\Framework\App\Config\ReinitableConfigInterface $storeConfig,
        \Magento\Sales\Model\OrderIncrementIdChecker $orderIncrementIdChecker,
        \M2E\M2ECloudMagentoConnector\Helper\Data\GlobalData $globalDataHelper,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\ItemFactory $magentoQuoteItemFactory
    ) {
        $this->proxyOrder = $proxyOrder;
        $this->currency = $currency;
        $this->magentoCurrencyFactory = $magentoCurrencyFactory;
        $this->calculation = $calculation;
        $this->storeConfig = $storeConfig;
        $this->quoteManager = $quoteManager;
        $this->orderIncrementIdChecker = $orderIncrementIdChecker;
        $this->configuratorFactory = $configuratorFactory;
        $this->globalDataHelper = $globalDataHelper;
        $this->magentoQuoteItemFactory = $magentoQuoteItemFactory;
    }

    public function __destruct()
    {
        if ($this->storeConfigurator === null) {
            return;
        }

        $this->storeConfigurator->restoreOriginalStoreConfigForOrder();
    }

    // ----------------------------------------

    public function build()
    {
        try {
            // do not change invoke order
            // ---------------------------------------
            $this->initializeQuote();
            $this->initializeCustomer();
            $this->initializeAddresses();

            $this->configureStore();
            $this->configureTaxCalculation();

            $this->initializeCurrency();
            $this->initializeShippingMethodData();
            $this->initializeQuoteItems();
            $this->initializePaymentMethodData();

            $this->quote = $this->quoteManager->save($this->quote);

            $this->prepareOrderNumber();

            return $this->quote;
            // ---------------------------------------
        } catch (\Throwable $e) {
            if (!isset($this->quote)) {
                throw $e;
            }

            // Remove ordered items from customer cart
            $this->quote->setIsActive(false);
            $this->quote->removeAllAddresses();
            $this->quote->removeAllItems();

            $this->quote->save();

            throw $e;
        }
    }

    //########################################

    private function initializeQuote()
    {
        $this->quote = $this->quoteManager->getBlankQuote();

        $this->quote->setCheckoutMethod($this->proxyOrder->getCheckoutMethod());
        $this->quote->setStore($this->proxyOrder->getStore());
        $this->quote->getStore()->setData('current_currency', $this->quote->getStore()->getBaseCurrency());

        /**
         * The quote is empty at this moment, so it is not need to collect totals
         */
        $this->quote->setTotalsCollectedFlag(true);
        $this->quote = $this->quoteManager->save($this->quote);
        $this->quote->setTotalsCollectedFlag(false);

        $this->quote->setIsM2eQuote(true);
        $this->quote->setIsNeedToSendEmail(false);
        $this->quote->setNeedProcessChannelTaxes(true);

        $this->quoteManager->replaceCheckoutQuote($this->quote);

        $this->globalDataHelper->unsetValue(self::PROCESS_QUOTE_ID);
        $this->globalDataHelper->setValue(self::PROCESS_QUOTE_ID, $this->quote->getId());
    }

    //########################################

    private function initializeCustomer()
    {
        $this->quote
            ->setCustomerId(null)
            ->setCustomerEmail($this->proxyOrder->getBuyerEmail())
            ->setCustomerFirstname($this->proxyOrder->getCustomerFirstName())
            ->setCustomerLastname($this->proxyOrder->getCustomerLastName())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(\Magento\Customer\Model\Group::NOT_LOGGED_IN_ID);
    }

    //########################################

    private function initializeAddresses()
    {
        $billingAddress = $this->quote->getBillingAddress();
        $billingAddress->addData($this->proxyOrder->getBillingAddressData());

        $billingAddress->setLimitCarrier($this->proxyOrder->getCarrierCode());
        $billingAddress->setShippingMethod($this->proxyOrder->getShippingMethodCode());
        $billingAddress->setCollectShippingRates(true);
        $billingAddress->setShouldIgnoreValidation(true);

        // ---------------------------------------

        $shippingAddress = $this->quote->getShippingAddress();
        $shippingAddress->setSameAsBilling(0);
        $shippingAddress->addData($this->proxyOrder->getShippingAddress());

        $shippingAddress->setLimitCarrier($this->proxyOrder->getCarrierCode());
        $shippingAddress->setShippingMethod($this->proxyOrder->getShippingMethodCode());
        $shippingAddress->setCollectShippingRates(true);
    }

    //########################################

    private function initializeCurrency()
    {
        if ($this->currency->isConvertible($this->proxyOrder->getCurrency(), $this->quote->getStore())) {
            $currentCurrency = $this->magentoCurrencyFactory->create()->load(
                $this->proxyOrder->getCurrency()
            );
        } else {
            $currentCurrency = $this->quote->getStore()->getBaseCurrency();
        }

        $this->quote->getStore()->setData('current_currency', $currentCurrency);
    }

    //########################################

    /**
     * Configure store (invoked only after address, customer and store initialization and before price calculations)
     */
    private function configureStore()
    {
        $this->storeConfigurator = $this
            ->configuratorFactory
            ->create($this->quote, $this->proxyOrder);

        $this->storeConfigurator->prepareStoreConfigForOrder();
    }

    //########################################

    private function configureTaxCalculation()
    {
        // this prevents customer session initialization (which affects cookies)
        // see Mage_Tax_Model_Calculation::getCustomer()
        $this->calculation->setCustomer($this->quote->getCustomer());
    }

    //########################################

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function initializeQuoteItems()
    {
        foreach ($this->proxyOrder->getItems() as $item) {
            $this->clearQuoteItemsCache();
            $quoteItemBuilder = $this->magentoQuoteItemFactory->create($this->quote, $item, $this->proxyOrder);
            $this->initializeQuoteItem($item, $quoteItemBuilder);
        }

        $allItems = $this->quote->getAllItems();
        $this->quote->getItemsCollection()->removeAllItems();

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($allItems as $item) {
            $item->save();
            $this->quote->getItemsCollection()->addItem($item);
        }
    }

    /**
     * @param OrderItemInterface $item
     * @param Item $quoteItemBuilder
     *
     * @throws \JsonException
     * @throws LocalizedException|\Exception
     */
    private function initializeQuoteItem(
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface $item,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Item $quoteItemBuilder
    ): void {
        // ---------------------------------------
        $product = $quoteItemBuilder->getProduct();
        $request = $quoteItemBuilder->getRequest();
        $productOriginalPrice = (float)$product->getPrice();

        $price = $this->proxyOrder->convertPriceToBase($item->getPrice());
        $product->setPrice($price);
        // ---------------------------------------

        $this->quote->setItemsCount($this->quote->getItemsCount() + 1);
        $this->quote->setItemsQty((float)$this->quote->getItemsQty() + $request->getQty());

        $result = $this->quote->addProduct($product, $request);
        if (is_string($result)) {
            throw new \Exception($result);
        }

        $quoteItem = $this->quote->getItemByProduct($product);
        if ($quoteItem === false) {
            return;
        }

        $quoteItem->setStoreId($this->quote->getStoreId());
        $quoteItem->setOriginalCustomPrice($item->getPrice());
        $quoteItem->setOriginalPrice($productOriginalPrice);
        $quoteItem->setBaseOriginalPrice($productOriginalPrice);
        $quoteItem->setNoDiscount(1);
        foreach ($quoteItem->getChildren() as $itemChildren) {
            $itemChildren->getProduct()->setTaxClassId($quoteItem->getProduct()->getTaxClassId());
        }

        $quoteItem->setAdditionalData($quoteItemBuilder->getAdditionalData($quoteItem));
    }

    /**
     * Mage_Sales_Model_Quote_Address caches items after each collectTotals call. Some extensions calls collectTotals
     * after adding new item to quote in observers. So we need clear this cache before adding new item to quote.
     */
    private function clearQuoteItemsCache()
    {
        foreach ($this->quote->getAllAddresses() as $address) {
            $address->unsetData('cached_items_all');
            $address->unsetData('cached_items_nominal');
            $address->unsetData('cached_items_nonnominal');
        }
    }

    //########################################

    private function initializeShippingMethodData()
    {
        $this->globalDataHelper->unsetValue('shipping_data');
        $this->globalDataHelper->setValue('shipping_data', $this->proxyOrder->getShippingData());
    }

    //########################################

    private function initializePaymentMethodData(): void
    {
        $quotePayment = $this->quote->getPayment();
        $quotePayment->importData($this->proxyOrder->getPaymentData());
    }

    //########################################

    private function prepareOrderNumber()
    {
        $orderNumber = $this->quote->getReservedOrderId();
        empty($orderNumber) && $orderNumber = $this->quote->getResource()->getReservedOrderId($this->quote);

        if ($this->orderIncrementIdChecker->isIncrementIdUsed($orderNumber)) {
            $orderNumber = $this->quote->getResource()->getReservedOrderId($this->quote);
        }

        $this->quote->setReservedOrderId($orderNumber);
    }
}
