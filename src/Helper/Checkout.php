<?php
/**
 * Copyright (c) 2020 Wamoco GmbH
 * See LICENSE.txt for license details.
 */
namespace Wamoco\TwigTheme\Helper;

class Checkout
{
    public function getPaymentMethods()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $paymentMethods = $objectManager->get("Magento\Payment\Helper\Data")->getStoreMethods();
        return $paymentMethods;
    }


    public function getShippingMethods()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $address = $objectManager->create("Magento\Customer\Model\Address");
        $carrierFinder = $objectManager->get("Magento\InstantPurchase\Model\ShippingMethodChoose\CarrierFinder");
        $shippingMethods = $carrierFinder->getCarriersForCustomerAddress($address);
        return $shippingMethods;
    }
}
