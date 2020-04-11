<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Filters;

class Qty extends \Twig\TwigFilter
{
    public function getCallable()
    {
        return function($qty) {
            return intval($qty);
        };
    }
}
