<?php
namespace Wamoco\TwigTheme\View\Helper;

class Contact extends \Magento\Contact\Helper\Data
{
    public function getName()
    {
        return ($this->getPostValue('name') ?: $this->getUserName());
    }

    public function getEmail()
    {
        return ($this->getPostValue('email') ?: $this->getUserEmail());

    }

    public function getTelephone()
    {
        return $this->getPostValue('telephone');

    }

    public function getComment()
    {
        return $this->getPostValue('comment');

    }


}
