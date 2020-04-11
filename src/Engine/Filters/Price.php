<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Filters;

class Price extends \Twig\TwigFilter
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * __construct
     *
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param mixed $name
     */
    public function __construct (
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        $name
    ) {
        parent::__construct($name);
        $this->priceCurrency = $priceCurrency;
    }

    public function getCallable()
    {
        return function($string) {
            return $this->priceCurrency->convertAndFormat($string, false);
        };
    }
}
