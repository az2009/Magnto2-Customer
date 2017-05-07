<?php

namespace Jbp\Customer\Block\Widget;

use \Magento\Customer\Block\Widget\Taxvat as WidgetTaxvat;

class Taxvat extends WidgetTaxvat
{
    
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('Jbp_Customer::widget/taxvat.phtml');
        
    }
    
}