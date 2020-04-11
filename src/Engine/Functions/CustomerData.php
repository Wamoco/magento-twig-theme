<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class CustomerData extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Customer\CustomerData\SectionPoolInterface
     */
    protected $sectionPool;

    /**
     * __construct
     *
     * @param \Magento\Customer\CustomerData\SectionPoolInterface $sectionPool
     * @param mixed $name
     */
    public function __construct (
        \Magento\Customer\CustomerData\SectionPoolInterface $sectionPool,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->sectionPool = $sectionPool;
    }

    public function getCallable()
    {
        return function($name) {
            return $this->sectionPool->getSectionsData($name);
        };
    }
}

