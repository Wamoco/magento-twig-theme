<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class CreateBlock extends \Twig\TwigFunction
{
    /**
     * __construct
     *
     * @param mixed $name
     */
    public function __construct (
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
    }

    public function getCallable()
    {
        return function($name, $args=[]) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            // filter null values
            foreach ($args as $key => $value) {
                if (is_null($value)) {
                    unset($args[$key]);
                }
            }
            return $objectManager->create($name, $args);
        };
    }
}

