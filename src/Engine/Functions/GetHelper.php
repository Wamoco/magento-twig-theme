<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetHelper extends \Twig\TwigFunction
{
    public function getCallable()
    {
        return function($name) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            return $objectManager->get($name);
        };
    }
}
