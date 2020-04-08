<?php
namespace Wamoco\TwigTheme\View\Functions;

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
