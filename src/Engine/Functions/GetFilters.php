<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetFilters extends \Twig\TwigFunction
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
        return function() {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $renderLayeredBlock = $objectManager->get('Magento\Swatches\Block\LayeredNavigation\RenderLayered');
            $leftNav = $this->layout->getBlock('catalog.leftnav');
            $filters = $leftNav->getFilters();
            foreach ($filters as $filter) {
                $eavAttribute = $filter->getData('attribute_model');
                if ($eavAttribute) {
                    $swatchData = $renderLayeredBlock->setSwatchFilter($filter)->getSwatchData();
                    if (array_key_exists('swatches', $swatchData)) {
                        foreach ($swatchData['swatches'] as &$swatch) {
                            $swatch['url'] = $swatchData['options'][$swatch['option_id']]['link'];
                        }
                    }
                    $filter->setSwatchData($swatchData);
                    $filter->setEavAttribte($eavAttribute);
                }
            }
            return $filters;
        };
    }
}
