<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Filters;

class EscapeUrl extends \Twig\TwigFilter
{
    /**
     * @var \Magento\Framework\View\Element\Template
     */
    protected $template;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\Element\Template $template
     * @param mixed $name
     */
    public function __construct (
        \Magento\Framework\View\Element\Template $template,
        $name
    ) {
        parent::__construct($name);
        $this->template = $template;
    }

    public function getCallable()
    {
        return function($string) {
            return $this->template->escapeUrl($string);
        };
    }
}
