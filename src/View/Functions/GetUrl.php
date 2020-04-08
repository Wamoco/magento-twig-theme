<?php
namespace Wamoco\TwigTheme\View\Functions;

class GetUrl extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * __construct
     *
     * @param $urlBuilder $name
     */
    public function __construct (
        \Magento\Framework\UrlInterface $urlBuilder,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->urlBuilder = $urlBuilder;
    }

    public function getCallable()
    {
        return function($route, $params=[]) {
            return $this->urlBuilder->getUrl($route, $params);
        };
    }
}
