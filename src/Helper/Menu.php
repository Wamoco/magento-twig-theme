<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Helper;

class Menu
{
    public function getTree()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $menuBlock = $objectManager->get('Magento\Theme\Block\Html\Topmenu');
        $menuBlock->getHtml();
        $menuTree = $menuBlock->getMenu();
        return $menuTree;
    }
}
