<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Plugin\Magento\Framework\App\Action;

use Psr\Log\LoggerInterface;

class Action
{
    /**
     * @var \Wamoco\TwigTheme\Engine\TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var \Wamoco\TwigTheme\Engine\Twig\Result\Page
     */
    protected $resultPage;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * __construct
     *
     * @param \Wamoco\TwigTheme\Engine\TemplateRepository $templateRepository
     * @param \Wamoco\TwigTheme\Engine\Twig\Result\Page $resultPage
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Wamoco\TwigTheme\Engine\TemplateRepository $templateRepository,
        \Wamoco\TwigTheme\Engine\Twig\Result\Page $resultPage,
        LoggerInterface $logger
    ) {
        $this->templateRepository = $templateRepository;
        $this->resultPage = $resultPage;
        $this->logger = $logger;
    }

    public function aroundExecute($subject, $proceed)
    {
        // call parent
        $originalResult = $proceed();
        $this->logger->debug("Trying to find template for", ['subject' => get_class($subject)]);
        $template = $this->templateRepository->get($subject);

        if ($template) {
            $this->resultPage->setTemplate($template);
            $this->logger->debug("Route to template", ['template' => $template]);
            if ($this->resultPage->templateExists()) {
                return $this->resultPage;
            }
            $this->logger->error("Template does not exist", ['template' => $template]);
        }
        return $originalResult;
    }
}
