<?php
namespace Wamoco\TwigTheme\View\Filters;

class Translate extends \Twig\TwigFilter
{
    public function getCallable()
    {
        return function($string) {
            return __($string);
        };
    }
}
