<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class RenderCmsBlock extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param mixed $name
     */
    public function __construct (
        \Magento\Framework\View\LayoutInterface $layout,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->layout = $layout;
    }

    public function getCallable()
    {
        return function($identifier) {
            $block = $this->layout->createBlock("Magento\Cms\Block\Block")
                ->setBlockId($identifier);
            return $block->toHtml();
        };
    }
}

