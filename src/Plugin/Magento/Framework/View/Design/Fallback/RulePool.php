<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Plugin\Magento\Framework\View\Design\Fallback;

class RulePool extends \Magento\Framework\View\Design\Fallback\RulePool
{
    protected function createTemplateFileRule()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $modularSwitchFactory = $objectManager->get("Magento\Framework\View\Design\Fallback\Rule\ModularSwitchFactory");

        $simpleFactory = $objectManager->get("Magento\Framework\View\Design\Fallback\Rule\SimpleFactory");
        $themeFactory = $objectManager->get("Magento\Framework\View\Design\Fallback\Rule\ThemeFactory");
        $moduleFactory = $objectManager->get("Magento\Framework\View\Design\Fallback\Rule\ModuleFactory");

        return $modularSwitchFactory->create(
            [
                'ruleNonModular' => new \Magento\Framework\View\Design\Fallback\Rule\Composite([
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/web/templates/overrides"])]),
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/templates"])]),
                ]),
                'ruleModular' => new \Magento\Framework\View\Design\Fallback\Rule\Composite([
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/web/templates/overrides"])]),
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/Wamoco_TwigTheme/web/templates/overrides"])]),
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/<module_name>/templates"])]),
                    $themeFactory->create(['rule' => $simpleFactory->create(['pattern' => "<theme_dir>/<module_name>/templates"])]),
                    $moduleFactory->create(['rule' => $simpleFactory->create(['pattern' => "<module_dir>/view/<area>/templates"])]),
                    $moduleFactory->create(['rule' => $simpleFactory->create(['pattern' => "<module_dir>/view/base/templates"])]),
                ])
            ]
        );
    }

}
