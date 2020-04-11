<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Helper;

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
