<?php

namespace Jbp\Customer\Block\Widget;


use Magento\Customer\Api\CustomerMetadataInterface;

use \Magento\Customer\Block\Widget\Taxvat as WidgetTaxvat;

class Taxvat extends WidgetTaxvat
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        \Magento\Customer\Model\Session $customerSession,        
        array $data = []
    ) {
            parent::__construct($context, $addressHelper, $customerMetadata, $data);
            $this->_isScopePrivate = true;
            $this->customerSession = $customerSession; 
    }
    
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('Jbp_Customer::widget/taxvat.phtml');
        
    }
    
    public function getRgStateenrollment()
    {        
        return $this->customerSession->getCustomer()->getData('rg_stateenrollment');        
    }
    
}