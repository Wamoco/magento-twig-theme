<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetFormKey extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formkey;

    /**
     * __construct
     *
     * @param \Magento\Framework\Data\Form\FormKey $formkey
     * @param mixed $name
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey $formkey,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->formkey = $formkey;
    }

    public function getCallable()
    {
        return function() {
            return $this->formkey->getFormKey();
        };
    }
}

