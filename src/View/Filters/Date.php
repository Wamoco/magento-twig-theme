<?php
namespace Wamoco\TwigTheme\View\Filters;

class Date extends \Twig\TwigFilter
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
        return function($date) {
            return $this->template->formatDate($date);
        };
    }
}
