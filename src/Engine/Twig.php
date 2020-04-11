<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Twig
{
    /**
     * @var \Wamoco\TwigTheme\Engine\FilesystemLoader
     */
    protected $loader;

    /**
     * __construct
     *
     * @param array $filters
     * @param array $functions
     */
    public function __construct (
        $filters = [],
        $functions = []
    ) {
        $this->loader = new \Wamoco\TwigTheme\Engine\FilesystemLoader();
        $this->twig = new Environment(
            $this->loader,
            ['debug' => true]
        );
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        foreach ($filters as $name => $filter) {
            $this->twig->addFilter($objectManager->create($filter, ['name' => $name]));
        }
        foreach ($functions as $name => $function) {
            $this->twig->addFunction($objectManager->create($function, ['name' => $name]));
        }
    }

    public function templateExists($path)
    {
        return $this->loader->exists($path);
    }

    public function render($template, $context = [])
    {
        return $this->twig->render($template, $context);
    }
}
