<?php

namespace M2E\M2ECloudMagentoConnector\Model\Magento\Quote;

class Manager
{
    protected \Magento\Quote\Api\CartRepositoryInterface $quoteRepository;
    protected \M2E\M2ECloudMagentoConnector\Model\Magento\Backend\Model\Session\Quote $sessionQuote;
    protected \Magento\Quote\Model\QuoteManagement $quoteManagement;
    protected \Magento\Checkout\Model\Session $checkoutSession;
    protected \Magento\Sales\Model\OrderFactory $orderFactory;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \M2E\M2ECloudMagentoConnector\Model\Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->sessionQuote = $sessionQuote;
        $this->quoteManagement = $quoteManagement;
        $this->checkoutSession = $checkoutSession;
        $this->orderFactory = $orderFactory;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getBlankQuote()
    {
        $this->clearQuoteSessionStorage();

        $quote = $this->sessionQuote->getQuote();
        $quote->setIsSuperMode(false);

        return $quote;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return \Magento\Framework\Model\AbstractExtensibleModel|\Magento\Sales\Api\Data\OrderInterface|null|object
     * @throws \Throwable
     */
    public function submit(\Magento\Quote\Model\Quote $quote)
    {
        try {
            $order = $this->quoteManagement->submit($quote);
            if ($order === null) {
                throw new \Exception(
                    'You are trying to create an order for Parent Product or Product that has been deleted.'
                );
            }

            return $order;
        } catch (\Throwable $exception) {
            $quote->setIsActive(false)
                  ->save();

            throw $exception;
        }
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function save(\Magento\Quote\Model\Quote $quote)
    {
        $this->quoteRepository->save($quote);

        return $quote;
    }

    public function replaceCheckoutQuote(\Magento\Quote\Model\Quote $quote)
    {
        $this->checkoutSession->replaceQuote($quote);
    }

    public function clearQuoteSessionStorage()
    {
        $this->sessionQuote->clearStorage();
    }
}
