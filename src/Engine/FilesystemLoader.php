<?php
namespace Wamoco\TwigTheme\Engine;

class FilesystemLoader extends \Twig\Loader\FilesystemLoader
{
    protected function findTemplate($name, $throw = true)
    {
        // TODO: security
        if (file_exists($name)) {
            return $name;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resolver = $objectManager->get("Magento\Framework\View\FileSystem");
        $path = $resolver->getStaticFileName("Wamoco_TwigTheme::templates/" . $name);
        return $path;
    }
}
