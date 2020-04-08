<?php
namespace Wamoco\TwigTheme\View\Functions;

class RenderElement extends \Twig\TwigFunction
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
        return function($name) {
            return $this->layout->renderElement($name);
        };
    }
}

