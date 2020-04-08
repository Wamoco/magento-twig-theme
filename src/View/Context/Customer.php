<?php
namespace Wamoco\TwigTheme\View\Context;

class Customer
{
    protected $checkoutSession;
    protected $customerSession;
    protected $wishlistData;
    protected $wishlist;
    protected $messageManager;
    protected $formkey;

    /**
     * __construct
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Model\Wishlist $wishlist
     * @param \Magento\Framework\Data\Form\FormKey $formkey
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Framework\Data\Form\FormKey $formkey,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->checkoutSession = $checkoutSession->start();
        $this->customerSession = $customerSession->start();
        $this->formkey = $formkey;
        $this->wishlist = $wishlist;
        $this->messageManager = $messageManager;
    }

    /**
     * getData
     * @return array
     */
    public function getData()
    {
        return [
            'wishlist' => $this->getWishlistData(),
            'compare_list' => $this->getCompareData(),
            'cart' => $this->getCartData(),
            'customer' => $this->getCustomerSessionData(),
            'messages' => $this->getMessages(),
            'formkey' => $this->formkey->getFormKey()
        ];
    }

    /**
     * getCustomerData
     *
     * @param string $kind
     * @return array
     */
    public function getCustomerData($kind = 'all')
    {
        $data = [];
        if ($kind == 'wishlist' || $kind == 'all') {
            $data['wishlist'] = $this->getWishlistData();
        }

        if ($kind == 'compare_list' || $kind == 'all') {
            $data['compare_list'] = $this->getCompareData();
        }

        if ($kind == 'cart' || $kind == 'all') {
            $data['cart'] = $this->getCartData();
        }

        if ($kind == 'customer' || $kind == 'all') {
            $data['customer'] = $this->getCustomerSessionData();
        }

        if ($kind == 'base' || $kind == 'all') {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $page = $objectManager->get("\Magento\Framework\View\Result\Page");
            $assetPath = $page->getLayout()->createBlock('Magento\Framework\View\Element\Template')->getViewFileUrl("Wamoco_TwigTheme");
            $formkey = $objectManager->get("\Magento\Framework\Data\Form\FormKey");
            $data['base'] = [
                'assetPath' => $assetPath,
                'formkey' => $formkey->getFormKey(),
            ];
        }

        $data['messages'] = $this->getMessages();
        return $data;
    }

    protected function getMessages()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $messageManager = $objectManager->get("Magento\Framework\Message\ManagerInterface");
        $interpretationStrategy = $objectManager->get("\Magento\Framework\View\Element\Message\InterpretationStrategyInterface");
        $messages = $this->getCookiesMessages();
        /** @var MessageInterface $message */

        foreach ($messageManager->getMessages(true)->getItems() as $message) {
            $messages[] = [
                'type' => $message->getType(),
                'text' => $interpretationStrategy->interpret($message),
            ];
        }
        return $messages;
    }
    /**
     * Return messages stored in cookies
     *
     * @return array
     */
    protected function getCookiesMessages()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $serializer = $objectManager->get("\Magento\Framework\Serialize\Serializer\Json");
        $cookieManager = $objectManager->get("Magento\Framework\Stdlib\CookieManagerInterface");
        $cookieMetadataFactory = $objectManager->get("Magento\Framework\Stdlib\Cookie\CookieMetadataFactory");
        $messages = $cookieManager->getCookie("mage-messages");
        if (!$messages) {
            return [];
        }
        $messages = $serializer->unserialize($messages);
        if (!is_array($messages)) {
            $messages = [];
        }
        $publicCookieMetadata = $cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDurationOneYear();
        $publicCookieMetadata->setPath('/');
        $publicCookieMetadata->setHttpOnly(false);

        $cookieManager->setPublicCookie(
            'mage-messages',
            $serializer->serialize([]),
            $publicCookieMetadata
        );
        return $messages;
    }

    /**
     * getWishlistData
     * @return array
     */
    protected function getWishlistData()
    {
        $customerId = $this->customerSession->getCustomerId();
        $items = [];
        $this->wishlist->loadByCustomerId($customerId);
        $wishlistItems = $this->wishlist->getItemCollection();

        $wishlistItemsCount = $wishlistItems->getSize();
        foreach($wishlistItems as $item) {
            $productId = intval($item->getProductId());
            $itemId = intval($item->getId());
            $items[] = ['item_id' => $itemId, 'product_id' => $productId];
        }

        return [
            'count' => $wishlistItemsCount,
            'items' => $items
        ];
    }

    /**
     * getCompareData
     * @return array
     */
    protected function getCompareData()
    {
        // TODO
        return [
            'count' => 0,
            'items' => []
        ];

        $items = [];
        $helper = Mage::helper('catalog/product_compare');
        foreach ($helper->getItemCollection() as $item) {
            $items[] = [
                'id' => $item->getId(),
                'product_id' => $item->getProductId(),
                'name' => Mage::helper('catalog/output')->productAttribute($item, $item->getName(), 'name'),
                'url' => $item->getProductUrl()
            ];

        }
        return [
            'count' => count($items),
            'items' => $items
        ];
    }

    /**
     * getCartData
     * @return array
     */
    protected function getCartData()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quote = $this->checkoutSession->getQuote();

        $quote->collectTotals();
        $totals = $quote->getTotals();


        /* $myCart = $objectManager->get(\Magento\Checkout\Model\Cart::class); */
        /* $cartQuote = $myCart->getQuote(); */
        /* $cartQuote->collectTotals(); */
        /* $totals = $cartQuote->getTotals(); */



        $items = [];
        $itemsCount = 0;
        foreach ($quote->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $itemsCount += $item->getQty();
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $data = $item->getData();
            $items[] = [
                'item_id' => $item->getId(),
                'product_id' => $product->getId(),
                'options' => $options,
                'sku' => $product->getSku(),
                'price' => $product->getFinalPrice(),
                'row_total' => $item->getRowTotal(),
                'tax_amount' => $item->getTaxAmount(),
                'tax_percent' => $item->getTaxPercent(),
                'name' => $product->getName(),
                'qty' => $item->getQty(),
                // TODO fix url building:
                'image_url' => "/pub/media/catalog/product/" . $item->getProduct()->getSmallImage(),
                'url' => $product->getProductUrl(),
                'show_edit_link' => $product->getCustomOption('catbundle_selection_ids') != null // TODO: coupling to Catbundle ?
            ];
        }

        $quoteIdToMaskedQuoteId = $objectManager->get("Magento\Quote\Model\QuoteIdToMaskedQuoteId");
        $totals = array_map(function($total) {
            return [
                'title' => $total->getTitle(),
                'value' => floatval($total->getValue())
            ];
        }, $quote->getTotals());

        $quoteId = null;
        if ($quote->getId()) {
            $quoteId = $quoteIdToMaskedQuoteId->execute($quote->getId());
        }
        return [
            'count' => $itemsCount,
            'coupon_code' => $quote->getCouponCode(),
            'quoteId' => $quoteId,
            'items' => $items,
            'totals' => $totals
        ];
    }

    protected function getCustomerSessionData()
    {
        $session = $this->customerSession;
        $customer = $session->getCustomer();
        return [
            'isLoggedIn' => $session->isLoggedIn(),
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'name' => sprintf("%s %s", $customer->getFirstname(), $customer->getLastname()),
            'email' => $customer->getEmail(),
            'addresses' => $this->getCustomerAddresses(),
            'defaultBilling' => $this->getDefaultBilling(),
            'defaultShipping' => $this->getDefaultShipping(),
        ];
    }

    protected function getCustomerAddresses()
    {
        $session = $this->customerSession;
        if ($session->isLoggedIn()) {
            return array_map(function($address) {
                return $address->getData();
            }, $session->getCustomer()->getAddresses());
        }
        return [];
    }

    protected function getDefaultBilling()
    {
        $session = $this->customerSession;
        if ($session->isLoggedIn()) {
            return $session->getCustomer()->getDefaultBilling();
        }
        return null;
    }

    protected function getDefaultShipping()
    {
        $session = $this->customerSession;
        if ($session->isLoggedIn()) {
            return $session->getCustomer()->getDefaultShipping();
        }
        return null;
    }
}
