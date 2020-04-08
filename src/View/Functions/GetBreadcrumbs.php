<?php
namespace Wamoco\TwigTheme\View\Functions;

use Magento\Catalog\Helper\Data as CatalogHelper;

class GetBreadcrumbs extends \Twig\TwigFunction
{
    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * __construct
     *
     * @param CatalogHelper $catalogHelper
     * @param mixed $name
     */
    public function __construct (
        CatalogHelper $catalogHelper,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->catalogHelper = $catalogHelper;
    }

    public function getCallable()
    {
        return function() {
            $path = $this->catalogHelper->getBreadcrumbPath();
            return $path;
        };
    }
}
