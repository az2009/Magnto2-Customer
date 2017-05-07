<?php

namespace Jbp\Customer\Block\Widget;

use \Magento\Customer\Block\Widget\Name as WidgetName;

class Name extends WidgetName
{
    
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('Jbp_Customer::widget/name.phtml');
        
    }
    
}