<?php

namespace Jbp\Customer\Model\FindAddress;

use Magento\Framework\HTTP\ZendClient;

use \Magento\Framework\Logger\Monolog;

class Api extends \Magento\Framework\HTTP\ZendClient 
{
    
    protected $_findAddress;
    
    protected $_logger;
    
    public function __construct(
        \Jbp\Customer\Helper\FindAddress $findAddress,
        Monolog $log
    ) {
        
        $this->_findAddress = $findAddress;
        
        $this->_logger = $log;
        
        parent::__construct($this->_findAddress->getUrl(),null);
        
    }
    
    public function getAddress($postCode)
    {

        try {
            
            $this->setParameterPost('cepEntrada', $postCode);
            
            $this->setParameterPost('tipoCep', '');
            
            $this->setParameterPost('cepTemp', '');
            
            $this->setParameterPost('metodo', 'buscarCep');
            
            $this->setMethod(self::POST);
            
            $response = $this->request();
            
            $body = $response->getBody();
            
            $address = $this->_findAddress->formatAddress($body);
            
            if($address !== false)            
                return $address;
            
            return false;
            
        } catch (\Exception $e) {
            
            $this->_logger->addError($e->getMessage());
            
            return false;
            
        }
        
    }
    
}






















