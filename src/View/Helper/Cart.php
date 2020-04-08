<?php
namespace Wamoco\TwigTheme\View\Helper;

class Cart extends \Magento\Checkout\Helper\Cart
{
    public function getDeletePostJson($item)
    {
        $item = new \Magento\Framework\DataObject(['id' => $item['item_id']]);
        return parent::getDeletePostJson($item);
    }
}

