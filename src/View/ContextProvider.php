<?php
namespace Wamoco\TwigTheme\View;

class ContextProvider
{
    protected $regions = [];

    public function __construct(
        $regions = []
    ) {
        $this->regions = $regions;
    }

    public function getAll()
    {
        $result = [];
        foreach ($this->regions as $regionKey => $provider) {
            $result[$regionKey] = $this->getProviderByClassname($provider)->getData();
        }

        return $result;
    }

    public function getProviderByClassname($classname)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager->get($classname);
    }

    public function getRegion($regionKey)
    {
        if (array_key_exists($regionKey, $this->regions)) {
            return $this->getProviderByClassname($this->regions[$regionKey])->getData();
        }
        return [];
    }
}
