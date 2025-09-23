<?php

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Quote;

use M2E\M2ECloudMagentoConnector\Model\Magento\Quote\Total\RoundTaxPercent;

class Item extends \Magento\Framework\DataObject
{
    public const PRODUCT_TAX_CLASS_ID_NONE = 0;

    private ?\Magento\Catalog\Api\Data\ProductInterface $product = null;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Tax\Helper $taxHelper;
    private \Magento\Tax\Model\Calculation $calculation;
    private \Magento\Quote\Model\Quote $quote;
    private \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface $proxyItem;
    private \M2E\M2ECloudMagentoConnector\Model\Magento\Tax\Rule\BuilderFactory $taxRuleBuilderFactory;
    private \Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    private \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject $proxyOrder;

    public function __construct(
        \M2E\M2ECloudMagentoConnector\Model\Magento\Tax\Rule\BuilderFactory $taxRuleBuilderFactory,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Tax\Helper $taxHelper,
        \Magento\Tax\Model\Calculation $calculation,
        \Magento\Quote\Model\Quote $quote,
        \M2E\M2ECloudMagentoConnector\Api\Data\OrderItemInterface $proxyItem,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \M2E\M2ECloudMagentoConnector\Model\Order\ProxyObject $proxyOrder
    ) {
        parent::__construct();
        $this->taxHelper = $taxHelper;
        $this->calculation = $calculation;
        $this->quote = $quote;
        $this->proxyItem = $proxyItem;
        $this->taxRuleBuilderFactory = $taxRuleBuilderFactory;
        $this->productRepository = $productRepository;
        $this->proxyOrder = $proxyOrder;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Exception
     */
    public function getProduct(): \Magento\Catalog\Api\Data\ProductInterface
    {
        if ($this->product !== null) {
            return $this->product;
        }

        $this->product = $this->productRepository->get($this->proxyItem->getSku());

        // tax class id should be set before price calculation
        return $this->setTaxClassIntoProduct($this->product);
    }

    // ---------------------------------------

    public function setTaxClassIntoProduct(
        \Magento\Catalog\Api\Data\ProductInterface $product
    ): \Magento\Catalog\Api\Data\ProductInterface {
        $proxyOrder = $this->proxyOrder;
        $itemTaxRate = $this->getTaxRateOfProxyItem();
        $isOrderHasTax = $this->proxyOrder->hasTax();
        $hasRatesForCountry = $this->taxHelper->hasRatesForCountry($this->quote->getShippingAddress()->getCountryId());
        $calculationBasedOnOrigin = $this->taxHelper->isCalculationBasedOnOrigin($this->quote->getStore());

        if (
            $proxyOrder->isTaxModeNone()
            || ($proxyOrder->isTaxModeChannel() && $itemTaxRate <= 0)
            || ($proxyOrder->isTaxModeMagento() && !$hasRatesForCountry && !$calculationBasedOnOrigin)
            || ($proxyOrder->isTaxModeMixed() && $itemTaxRate <= 0 && $isOrderHasTax)
        ) {
            /** @psalm-suppress UndefinedInterfaceMethod */
            return $product->setTaxClassId(self::PRODUCT_TAX_CLASS_ID_NONE);
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        if (
            $proxyOrder->isTaxModeMagento()
            || $itemTaxRate <= 0
            || $itemTaxRate == $this->getProductTaxRate($product->getTaxClassId())
        ) {
            return $product;
        }

        // Create tax rule according to channel tax rate
        // ---------------------------------------
        $taxRuleBuilder = $this->taxRuleBuilderFactory->create();
        $taxRuleBuilder->buildProductTaxRule(
            $itemTaxRate,
            $this->quote->getShippingAddress()->getCountryId(),
            $this->quote->getCustomerTaxClassId()
        );

        $taxRule = $taxRuleBuilder->getRule();
        $productTaxClasses = $taxRule->getProductTaxClasses();

        // ---------------------------------------

        /** @psalm-suppress UndefinedInterfaceMethod */
        return $product->setTaxClassId(array_shift($productTaxClasses));
    }

    /**
     * @return float|int
     */
    private function getTaxRateOfProxyItem()
    {
        $productPriceTaxRateObject = $this->proxyOrder->getProductPriceTaxRateObject();

        $rateValue = $productPriceTaxRateObject->getValue();
        if (!$productPriceTaxRateObject->isEnabledRoundingOfValue()) {
            return $rateValue;
        }

        $notRoundedTaxRateValue = $productPriceTaxRateObject->getNotRoundedValue();
        if ($rateValue !== $notRoundedTaxRateValue) {
            $this->quote->setData(
                RoundTaxPercent::PRODUCT_PRICE_TAX_DATA_KEY,
                $productPriceTaxRateObject
            );
        }

        return $notRoundedTaxRateValue;
    }

    private function getProductTaxRate($productTaxClassId)
    {
        $taxCalculator = $this->calculation;

        $request = $taxCalculator->getRateRequest(
            $this->quote->getShippingAddress(),
            $this->quote->getBillingAddress(),
            $this->quote->getCustomerTaxClassId(),
            $this->quote->getStore()
        );
        $request->setProductClassId($productTaxClassId);

        return $taxCalculator->getRate($request);
    }

    //########################################

    public function getRequest()
    {
        $request = new \Magento\Framework\DataObject();
        $request->setQty($this->proxyItem->getQty());

        return $request;
    }

    //########################################

    public function getAdditionalData(\Magento\Quote\Model\Quote\Item $quoteItem)
    {
        $additionalData = [''];//todo: add additional data?
        $existAdditionalData = is_string($quoteItem->getAdditionalData())
            ? json_decode($quoteItem->getAdditionalData(), true)
            : [];

        return json_encode(array_merge($existAdditionalData, $additionalData), JSON_THROW_ON_ERROR);
    }
}
