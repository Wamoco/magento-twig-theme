<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

use Magento\Framework\View\Asset\GroupedCollection;

class GetJs extends \Twig\TwigFunction
{
    /**
     * @var string
     */
    protected $template;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\Element\Template $template
     * @param mixed $name
     */
    public function __construct(
        \Magento\Framework\View\Element\Template $template,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->template = $template;
    }

    public function getCallable()
    {
        return function($name) {
            return $this->template->getViewFileUrl($name);
        };
    }
}
