<?php

namespace Jbp\Customer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper 
{
    
    public function getLabelAddressByRow($row)
    {

        switch ($row) {
            
            case 1:
                $msg = __('Address');
            break;
            
            case 2:
                $msg = __('Number');
            break;
            
            case 3:
                $msg = __('Complement');
            break;
            
            case 4:
                $msg = __('Neighborhood');
            break;
            
        }
        
        return $msg;
        
    }
    
}