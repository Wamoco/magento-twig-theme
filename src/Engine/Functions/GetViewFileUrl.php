<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetViewFileUrl extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Framework\View\Element\Template
     */
    protected $template;

    /**
     * __construct
     *
     * @param $template $name
     */
    public function __construct (
        \Magento\Framework\View\Element\Template $template,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->template = $template;
    }

    public function getCallable()
    {
        return function($arg = null) {
            return $this->template->getViewFileUrl($arg);
        };
    }
}
