<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetProduct extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * __construct
     *
     * @param \Magento\Catalog\Model\ProductFactory
     * @param \Magento\Framework\Registry
     * @param mixed $name
     */
    public function __construct (
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $registry,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->productFactory = $productFactory;
        $this->registry = $registry;
    }

    public function getCallable()
    {
        return function($idOrSku = null) {
            if (!$idOrSku) {
                // find product from registry
                return $this->registry->registry('product');
            }
            $product = $this->productFactory->create();
            $productId = is_string($idOrSku) ? $product->getIdBySku($idOrSku) : $idOrSku;

            return $product->load($productId);
        };
    }
}
