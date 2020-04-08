<?php
namespace Wamoco\TwigTheme\View\Context;

class Base
{
    /**
     * @var string
     */
    protected $template;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\Element\Template $template
     */
    public function __construct(
        \Magento\Framework\View\Element\Template $template
    ) {
        $this->template = $template;
    }

    /**
     * getData
     * @return array
     */
    public function getData()
    {
        return [
            'assetPath' => $this->template->getViewFileUrl("Wamoco_TwigTheme"),
            'baseViewUrl' => $this->template->getViewFileUrl(''),
            'baseUrl' => $this->template->getBaseUrl()
        ];
    }
}
