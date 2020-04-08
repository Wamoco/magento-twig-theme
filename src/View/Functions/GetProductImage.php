<?php
namespace Wamoco\TwigTheme\View\Functions;

class GetProductImage extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $helper;

    /**
     * __construct
     *
     * @param \Magento\Catalog\Helper\Image $helper
     * @param mixed $name
     */
    public function __construct (
        \Magento\Catalog\Helper\Image $helper,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->helper = $helper;
    }

    public function getCallable()
    {
        return function($productOrFile, $w = null, $h = null) {
            if (is_string($productOrFile)) {
                $productImage = $this->helper
                    ->setImageFile($productOrFile);
            } else {
                $productImage = $this->helper
                    ->init($productOrFile, 'product')
                    ->setImageFile($productOrFile->getImage());
            }

            $productImage
                ->constrainOnly(TRUE)
                ->keepAspectRatio(TRUE)
                ->keepTransparency(TRUE)
                ->keepFrame(FALSE);
            if ($w && $h) {
                $productImage->resize($w, $h);
            }

            return $productImage->getUrl();
        };
    }
}

