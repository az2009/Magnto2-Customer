<?php

namespace Jbp\Customer\Block\Widget;

use \Magento\Customer\Block\Widget\Gender as WidgetGender;

class Gender extends WidgetGender
{
    
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('Jbp_Customer::widget/gender.phtml');
        
    }
    
}