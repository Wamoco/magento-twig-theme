<?php
namespace Wamoco\TwigTheme\View\Context;

class Frontend
{
    /**
     * @var \Magento\Framework\View\Result\Page
     */
    protected $page;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\Result\Page $page
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Store\Model\StoreManagerInterface $store
     */
    public function __construct (
        \Magento\Framework\App\View $view,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->page = $view->getPage();
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    public function getData()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $translateJs = $objectManager->get("\Magento\Translation\Block\Js");
        $translateJs->setTemplate("Wamoco_TwigTheme::translate.phtml");
        $translateJsHtml = $translateJs->toHtml();

        $format = $objectManager->get("Magento\Framework\Locale\Format");
        $priceFormatJson = json_encode($format->getPriceFormat());
        return [
            'menuTree' => $this->getMenuTree(),
            'breadcrumbs' =>  [],
            'searchQuery' => $this->getSearchQuery(),
            'translateJsHtml' => $translateJsHtml,
            'priceFormatJson' => $priceFormatJson
        ];
    }

    public function getMenuTree()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $menuBlock = $objectManager->get('Magento\Theme\Block\Html\Topmenu');
        $menuBlock->getHtml();
        $menuTree = $menuBlock->getMenu();
        return $menuTree;
    }

    public function getSearchQuery()
    {
        $searchQuery = $this->request->getParam('q');
        return $searchQuery;
    }

}
