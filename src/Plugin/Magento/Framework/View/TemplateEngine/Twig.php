<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Plugin\Magento\Framework\View\TemplateEngine;

class Twig implements \Magento\Framework\View\TemplateEngineInterface
{
    /**
     * @var \Wamoco\TwigTheme\Engine\Twig
     */
    protected $twig;

    /**
     * __construct
     *
     * @param \Wamoco\TwigTheme\Engine\Twig $twig
     */
    public function __construct(\Wamoco\TwigTheme\Engine\Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * render
     *
     * @param \Magento\Framework\View\Element\BlockInterface $block
     * @param mixed $templateFile
     * @param array $dictionary
     */
    public function render(
        \Magento\Framework\View\Element\BlockInterface $block,
        $templateFile,
        array $dictionary = []
    ) {
        $context = array_merge($dictionary, ['block' => $block]);
        if (!$this->templateExists($templateFile)) {
            throw new \Exception("Template " . $templateFile . " could not be found");
        }
        return $this->twig->render($templateFile, $context);
    }

    /**
     * templateExists
     *
     * @param string $templateFile
     * @return boolean
     */
    public function templateExists($templateFile)
    {
        return $this->twig->templateExists($templateFile);
    }
}
