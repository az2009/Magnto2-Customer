<?php

namespace Jbp\Customer\Model\Customer;

use \Magento\Framework\Model\AbstractModel;

use \Magento\Framework\App\ObjectManager;

class Customer extends AbstractModel
{

    protected $_taxvat;
    
    protected $_customer;
    
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Jbp\Customer\Helper\Taxvat $taxvat,
        \Magento\Customer\Model\Customer $customer
    ) {
            
        $this->_taxvat = $taxvat;
        
        $this->_customer = $customer;
        
        parent::__construct($context, $registry);
            
    }
    
    public function getCustomerByTaxvat($taxvat)
    {
        
        $customer= ObjectManager::getInstance()
                              ->get('Magento\Customer\Model\Customer')
                              ->getCollection()
                              ->addAttributeToFilter('taxvat',$taxvat)
                              ->getFirstItem();
        
        if ($customer->getId()) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    public function validateCustomer($taxvat)
    {
        
        $result = [];
        
        $this->_taxvat->setTaxvat($taxvat);
        
        if ($this->_taxvat->valida()) {
            
            if (!$this->getCustomerByTaxvat($taxvat)) {
                
                return [
                    'result' => 'success',
                ];

            } else {
                
                return [
                    'result' => 'customer_exists',
                ];
                
            }
            
        } else {
            
            return [
                'result' => 'taxvat_invalid',
            ];

        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}