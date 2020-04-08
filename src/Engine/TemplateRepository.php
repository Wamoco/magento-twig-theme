<?php
namespace Wamoco\TwigTheme\Engine;

class TemplateRepository
{
    /**
     * @var \Wamoco\TwigTheme\Engine\Config
     */
    protected $themeConfig;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * __construct
     *
     * @param \Wamoco\TwigTheme\Engine\Config $themeConfig
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \Wamoco\TwigTheme\Engine\Config $themeConfig,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->themeConfig = $themeConfig;
        $this->request = $request;
    }

    /**
     * get
     *
     * @param $actionInstance
     * @return string|null
     */
    public function get($actionInstance)
    {
        $currentUrl = $this->request->getPathInfo();

        if ($currentUrl != '/' && substr($currentUrl, -1) == '/') {
            // recursively remove slashes when its more than 1 slash
            while(substr($currentUrl, -1) == '/') {
                $currentUrl = substr($currentUrl, 0, -1);
            }
        }

        $routes = $this->themeConfig->getRoutes();
        if (array_key_exists($currentUrl, $routes)) {
            $template = $routes[$currentUrl];
            return $template;
        }

        foreach ($this->themeConfig->getControllers() as $action => $template) {
            if ($actionInstance instanceof $action) {
                return $template;
            }
        }
        return null;
    }
}
