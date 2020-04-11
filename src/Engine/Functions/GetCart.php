<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetCart extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Quote\Model\QuoteIdToMaskedQuoteId
     */
    protected $quoteIdToMaskedQuoteId;

    /**
     * @var array
     */
    protected $cachedResult = null;

    /**
     * __construct
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Model\QuoteIdToMaskedQuoteId $quoteIdToMaskedQuoteId
     * @param mixed $name
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Model\QuoteIdToMaskedQuoteId $quoteIdToMaskedQuoteId,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->checkoutSession = $checkoutSession->start();
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }

    public function getCallable()
    {
        return function() {
            if (!$this->cachedResult) {
                $this->cachedResult = $this->getCartData();
            }
            return $this->cachedResult;
        };
    }

    /**
     * getCartData
     * @return array
     */
    protected function getCartData()
    {
        $quote = $this->checkoutSession->getQuote();
        $quote->collectTotals();
        $totals = $quote->getTotals();

        $items = [];
        $itemsCount = 0;
        foreach ($quote->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $itemsCount += $item->getQty();
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $data = $item->getData();
            $items[] = [
                'item_id' => $item->getId(),
                'product_id' => $product->getId(),
                'options' => $options,
                'sku' => $product->getSku(),
                'price' => $product->getFinalPrice(),
                'row_total' => $item->getRowTotal(),
                'tax_amount' => $item->getTaxAmount(),
                'tax_percent' => $item->getTaxPercent(),
                'name' => $product->getName(),
                'qty' => $item->getQty(),
                // TODO fix url building:
                'image_url' => "/pub/media/catalog/product/" . $item->getProduct()->getSmallImage(),
                'url' => $product->getProductUrl()
            ];
        }

        $totals = array_map(function($total) {
            return [
                'title' => $total->getTitle(),
                'value' => floatval($total->getValue())
            ];
        }, $quote->getTotals());

        $quoteId = null;
        if ($quote->getId()) {
            $quoteId = $this->quoteIdToMaskedQuoteId->execute($quote->getId());
        }
        return [
            'count' => $itemsCount,
            'coupon_code' => $quote->getCouponCode(),
            'quoteId' => $quoteId,
            'items' => $items,
            'totals' => $totals
        ];
    }
}
