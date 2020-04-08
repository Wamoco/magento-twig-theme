<?php
namespace Wamoco\TwigTheme\Plugin\Magento\Framework\Webapi;

class Authorization extends \Magento\Framework\Webapi\Authorization
{
    /**
     * Changed logic, to allow if there is any rule present, not fail on the first one
     * @see inherited
     */
    public function isAllowed($aclResources)
    {
        foreach ($aclResources as $resource) {
            if ($this->authorization->isAllowed($resource)) {
                return true;
            }
        }
        return false;
    }
}
