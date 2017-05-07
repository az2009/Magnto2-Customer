<?php

namespace Jbp\Customer\Controller\Core;

use Magento\Framework\App\Action\Action as AbstractAction;

class Action extends AbstractAction
{
    
    protected $_resultJsonFactory;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        
        $this->_resultJsonFactory = $resultJsonFactory;
        
        parent::__construct($context);
            
    }
    
    public function execute()
    {
        
    }
    
    public function sendResponse(Array $data, $code = 200)
    {
        
        return  $this->_resultJsonFactory
                     ->create()
                     ->setHttpResponseCode($code)
                     ->setData($data);
        
    }
    
}

