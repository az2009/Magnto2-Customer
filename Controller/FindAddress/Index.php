<?php

namespace Jbp\Customer\Controller\FindAddress;

use Jbp\Customer\Controller\Core\Action;

class Index extends Action
{

    protected $_api;
    
    protected $_taxvat;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Jbp\Customer\Model\FindAddress\Api $api
    ) {
        
        $this->_api = $api;
        
        parent::__construct($context,$resultJsonFactory);
        
    }

    public function execute()
    {
     
        $result = [];
        
        $postcode = $this->getRequest()->getParam('postcode');
        
        if ( empty($postcode) || strlen(trim($postcode)) != 8 ) {
            
            $result['error'] = __('Please enter a zip/code valid');
            
            return $this->sendResponse($result, 401);
            
        }
        
        $result = $this->_api->getAddress($postcode);
        
        if( $result !== false ) {
            
            return $this->sendResponse($result);
            
        }
        
    }
    
}