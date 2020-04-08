<?php
namespace Wamoco\TwigTheme\View\Functions;

class GetRequestParam extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct (
        \Magento\Framework\App\RequestInterface $request,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->request = $request;
    }

    public function getCallable()
    {
        return function($name) {
            return $this->request->getParam($name);
        };
    }
}
