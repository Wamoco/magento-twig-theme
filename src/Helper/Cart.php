<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Helper;

class Cart extends \Magento\Checkout\Helper\Cart
{
    public function getDeletePostJson($item)
    {
        $item = new \Magento\Framework\DataObject(['id' => $item['item_id']]);
        return parent::getDeletePostJson($item);
    }
}

