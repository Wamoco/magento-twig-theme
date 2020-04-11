<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Engine\Functions;

class GetCustomer extends \Twig\TwigFunction
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var array
     */
    protected $cachedResult = null;

    /**
     * __construct
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param mixed $name
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        $name
    ) {
        parent::__construct($name, null, ['is_safe' => ['all']]);
        $this->customerSession = $customerSession->start();
    }

    public function getCallable()
    {
        return function() {
            if (!$this->cachedResult) {
                $session = $this->customerSession;
                $customer = $session->getCustomer();
                $this->cachedResult = [
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
            return $this->cachedResult;
        };
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
