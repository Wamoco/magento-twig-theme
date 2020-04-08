<?php
namespace Wamoco\TwigTheme\View\Functions;

class GetProduct extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * __construct
     *
     * @param \Magento\Catalog\Model\ProductFactory
     * @param mixed $name
     */
    public function __construct (
        \Magento\Catalog\Model\ProductFactory $productFactory,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->productFactory = $productFactory;
    }

    public function getCallable()
    {
        return function($idOrSku) {
            $product = $this->productFactory->create();
            $productId = is_string($idOrSku) ? $product->getIdBySku($idOrSku) : $idOrSku;

            return $product->load($productId);
        };
    }
}
