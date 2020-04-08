<?php
namespace Wamoco\TwigTheme\View\Functions;

class GetBlock extends \Twig\TwigFunction
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
            $block = $this->layout->getBlock($name);
            if (!$block) {
                throw new \Exception("Block " . $name . " could not be found");
            }
            return $block;
        };
    }
}

