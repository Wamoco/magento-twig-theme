<?php
namespace Wamoco\TwigTheme\Engine;

use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\View\DesignLoader;
use Magento\Framework\View\FileSystem;
use Psr\Log\LoggerInterface;

class Config
{
    public const CONFIG_FILE = "config.xml";

    /**
     * @var FrontendInterface
     */
    protected $cache;

    /**
     * @var \Magento\Framework\View\DesignLoader
     */
    protected $designLoader;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $controllers = [];

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $rewrites = [];

    /**
     * __construct
     *
     * @param FrontendInterface $cache
     * @param DesignLoader $designLoader
     * @param FileSystem $fileSystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        FrontendInterface $cache,
        DesignLoader $designLoader,
        FileSystem $fileSystem,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->designLoader = $designLoader;
        $this->fileSystem = $fileSystem;
        $this->logger = $logger;
        $this->designLoader->load();
        if (!$this->loadFromCache()) {
            $this->loadFromFile();
            $this->cacheConfig();
        }
    }

    public function getControllers()
    {
        return $this->controllers;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getRewrites()
    {
        return $this->rewrites;
    }

    public function loadFromCache()
    {
        try {
            $config = $this->cache->load($this->getCacheKey());
            if ($config) {
                $config = json_decode($config, true);
                $this->controllers = $config['controllers'];
                $this->routes      = $config['routes'];
                $this->rewrites    = $config['rewrites'];
                return true;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return false;
    }

    public function cacheConfig()
    {
        $config = [
            'controllers' => $this->controllers,
            'routes'      => $this->routes,
            'rewrites'    => $this->rewrites,
        ];
        $this->cache->save(json_encode($config), $this->getCacheKey(), []);
    }

    protected function loadFromFile()
    {
        try {
            $path = $this->fileSystem->getFilename(self::CONFIG_FILE);
            if (!$path) {
                throw new \Exception("no theme config.xml found");
            } else {
                $configContent = file_get_contents($path);
                $this->extractData($configContent);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    protected function extractData($configContent)
    {
        if (!empty($configContent)) {
            $dom = new \DOMDocument();
            $dom->loadXML($configContent);
            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query('controllers/item') as $controller) {
                $name = $controller->getAttribute('name');
                $template = $controller->nodeValue;
                $this->controllers[$name] = $template;
            }

            foreach ($xpath->query('routes/item') as $route) {
                $name = $route->getAttribute('name');
                $template = $route->nodeValue;
                $this->routes[$name] = $template;
            }
            foreach ($xpath->query('rewrites/item') as $route) {
                $name = $route->getAttribute('name');
                $path = $route->nodeValue;
                $this->rewrites[$name] = $path;
            }
        }
    }

    protected function getCacheKey()
    {
        return "wamoco-theme-config";
    }
}
