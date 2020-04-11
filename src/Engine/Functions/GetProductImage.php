<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetProductImage extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $helperFactory;

    /**
     * __construct
     *
     * @param \Magento\Catalog\Helper\ImageFactory $helperFactory
     * @param mixed $name
     */
    public function __construct (
        \Magento\Catalog\Helper\ImageFactory $helperFactory,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->helperFactory = $helperFactory;
    }

    public function getCallable()
    {
        return function($productOrFile, $w = null, $h = null) {
            if (is_string($productOrFile)) {
                $productImage = $this->helperFactory->create()
                    ->setImageFile($productOrFile);
            } else {
                $productImage = $this->helperFactory->create()
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

