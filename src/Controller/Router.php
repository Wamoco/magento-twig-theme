<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ActionInterface;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Wamoco\TwigTheme\Engine\Config
     */
    protected $themeConfig;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * __construct
     *
     * @param \Wamoco\TwigTheme\Engine\Config $themeConfig
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        \Wamoco\TwigTheme\Engine\Config $themeConfig,
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory $urlRewriteFactory
    ) {
        $this->themeConfig = $themeConfig;
        $this->actionFactory = $actionFactory;
        $this->url = $url;
        $this->storeManager = $storeManager;
        $this->response = $response;
        $this->urlFinder = $urlFinder;
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * Match corresponding URL Rewrite and modify request.
     *
     * @param RequestInterface|HttpRequest $request
     *
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        $rewrite = $this->getRewrite(
            $request->getPathInfo(),
            $this->storeManager->getStore()->getId()
        );

        if ($rewrite === null) {
            //No rewrite rule matching current URl found, continuing with
            //processing of this URL.
            return null;
        }
        //Rule provides actual URL that can be processed by a controller.
        $request->setAlias(
            UrlInterface::REWRITE_REQUEST_PATH_ALIAS,
            $rewrite->getRequestPath()
        );
        $request->setPathInfo('/' . $rewrite->getTargetPath());
        return $this->actionFactory->create(
            \Magento\Framework\App\Action\Forward::class
        );
    }

    /**
     * @param string $requestPath
     * @param int $storeId
     * @return UrlRewrite|null
     */
    protected function getRewrite($requestPath, $storeId)
    {
        if ($requestPath != '/' && substr($requestPath, -1) == '/') {
            // recursively remove slashes when its more than 1 slash
            while(substr($requestPath, -1) == '/') {
                $requestPath = substr($requestPath, 0, -1);
            }
        }

        $rewrites = $this->themeConfig->getRewrites();

        if (array_key_exists($requestPath, $rewrites)) {
            $targetPath = $rewrites[$requestPath];
            $urlRewrite = $this->urlRewriteFactory->create();
            $urlRewrite->setTargetPath($targetPath);
            return $urlRewrite;
        }

        return null;
    }
}
