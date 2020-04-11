<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetRequest extends \Twig\TwigFunction
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
        return function($route, $params=[]) {
            return $this->request;
        };
    }
}
