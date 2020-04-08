<?php
namespace Wamoco\TwigTheme\Observer;

use Magento\Framework\Event\ObserverInterface;

class AjaxInterceptor implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Wamoco\TwigTheme\Engine\Twig\Context
     */
    protected $templateContext;

    /**
     * __construct
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Wamoco\TwigTheme\View\ContextProvider $templateContext
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Wamoco\TwigTheme\View\ContextProvider $templateContext
    ) {
        $this->request = $request;
        $this->templateContext = $templateContext;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->afterCartUpdate();
    }

    /**
     * afterCartUpdate
     */
    public function afterCartUpdate()
    {
        // magento is stupid; I cannot get events directy.
        // I will listen on cart updates and check if request was add, delete or update
        if ($this->isAjaxRequest()) {
            $url = $this->getRequest()->getRequestUri();
            $paths = [
                '/checkout/cart/add',
                '/checkout/sidebar/removeItem',
                '/checkout/cart/updatePost',
                '/checkout/cart/updateItemOptions',
                '/wishlist/index/add',
                '/wishlist/index/remove'
            ];
            foreach ($paths as $path) {
                if (strpos($url, $path) !== false) {

                    $additionalData = [];
                    if (strpos('/checkout/cart/updateItemOptions', $path) !== false) {
                        // redirect to cart, if item was updated
                        $additionalData['redirect'] = Mage::getUrl('checkout/cart');
                    }

                    $this->sendCartDataIfAjaxRequest($additionalData);
                    return;
                }
            }
        }
    }

    /**
     * sendCartDataIfAjaxRequest
     * @param array $additionalData
     */
    protected function sendCartDataIfAjaxRequest($additionalData = [])
    {
        if ($this->isAjaxRequest()) {
            $this->sendCartData($additionalData);
            Mage::getSingleton('checkout/session')->getMessages(true);
        }
    }

    /**
     * isAjaxRequest
     *
     * @return bool
     */
    public function isAjaxRequest()
    {
        return $this->getRequest()->getParam('isAjax', false);
    }

    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * sendCartData
     * @param array $additionalData
     * @param Mage_Core_Controller_Response_Http $response
     */
    protected function sendCartData($additionalData = [])
    {
        /* $data = [ */
        /*     'cart' => $this->templateContext->getRegion('customer')['cart'] */
        /* ]; */
        $data = [
            'customer' => $this->templateContext->getRegion('customer')
            /* 'cart' => $this->templateContext->getRegion('customer')['cart'] */
        ];
        echo json_encode(array_merge($data, $additionalData));
        die();
    }
}
