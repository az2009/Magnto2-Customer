<?php

namespace Jbp\Customer\Plugin;

use Magento\Framework\Message\ManagerInterface as MessageManager;

class CustomerDatePlugin
{
    
    private $messageManager;
    
    public function __construct(MessageManager $messageManager){        
        $this->messageManager = $messageManager;
    }
    
    public function beforeValidateValue($value)
    {      
        $this->messageManager->addSuccessMessage("teste");      
    }
    
}