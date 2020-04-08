<?php
namespace Wamoco\TwigTheme\View\Functions;

use Magento\Framework\View\Page\Config;

class GetHeadData extends \Twig\TwigFunction
{
    /**
     * @var Config
     */
    protected $pageConfig;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * __construct
     *
     * @param Config $pageConfig
     * @param \Magento\Framework\Escaper $escaper
     * @param mixed $name
     */
    public function __construct(
        Config $pageConfig,
        \Magento\Framework\Escaper $escaper,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->pageConfig = $pageConfig;
        $this->escaper = $escaper;
    }

    public function getCallable()
    {
        return function() {
            return [
                'title' => $this->escaper->escapeHtml($this->pageConfig->getTitle()->get())
            ];
        };
    }
}
