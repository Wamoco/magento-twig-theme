<?php
namespace Wamoco\TwigTheme\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Wamoco\TwigTheme\View\ContextProvider as TemplateContext;

class Data extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var TemplateContext
     */
    private $templateContext;

    /**
     * __construct
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param TemplateContext $templateContext
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        TemplateContext $templateContext
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->templateContext = $templateContext;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData([
            'base' => $this->templateContext->getRegion('base'),
            'customer' => $this->templateContext->getRegion('customer')
        ]);
    }
}
