<?php

namespace Jbp\Customer\Plugin\Checkout\Block\LayoutProcessor;

class Plugin
{
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, $result)
    {
        
        unset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['company']);
        
        unset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['billingAddress']['children']['billing-address-fieldset']['children']['company']);
        
        $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['sortOrder'] = 60;
        
        $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['billingAddress']['children']['billing-address-fieldset']['children']['postcode']['sortOrder'] = 60;
        
        return $result;
        
    }
  
}