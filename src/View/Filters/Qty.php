<?php
namespace Wamoco\TwigTheme\View\Filters;

class Qty extends \Twig\TwigFilter
{
    public function getCallable()
    {
        return function($qty) {
            return intval($qty);
        };
    }
}
