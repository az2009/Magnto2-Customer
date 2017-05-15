<?php

namespace Jbp\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Event\Observer as EventObserver;

class BeforeCustomer implements ObserverInterface
{

    protected $_taxvat = null;
    
    protected $_logger = null;
    
    protected $_messageManager = null;
    
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Jbp\Customer\Helper\Taxvat $taxvat,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_taxvat = $taxvat;
        
        $this->_messageManager = $messageManager;
        
        $this->_logger = $logger;
        
    }
    
    public function execute(EventObserver $observer)
    {
        
        $taxvat = $observer->getEvent()->getDataObject()->getTaxvat();
        
        if (!empty($taxvat)) {
            
            $this->_taxvat->setTaxvat($taxvat);
            
            $valida = $this->_taxvat->valida();
            
            if (!$valida) {
                
                $type = $this->_getTypeDocument($this->_taxvat->verifica_cpf_cnpj());
                
                $this->_messageManager->addNotice($this->_getMessage($type));
                
                $this->_logger->info($this->_getMessage($type));
                
                throw new \Exception($this->_getMessage($type));
                
            }
            
        }
        
        return $this;
        
    }
    
    protected function _getTypeDocument($taxvat)
    {
        if ($taxvat === false) {            
            return __('Taxvat');            
        }
        
        return $taxvat;
        
    }
    
    protected function _getMessage($type)
    {
        return __("Error {$type} invalid");
    }
    
}
