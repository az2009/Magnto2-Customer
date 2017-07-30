<?php

namespace Jbp\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Event\Observer as EventObserver;

use Magento\TestFramework\Event\Magento;

class BeforeCustomer implements ObserverInterface
{

    protected $_taxvat = null;
    
    protected $_logger = null;
    
    protected $_messageManager = null;
    
    protected $_customer = null;
    
    protected $_storeManager = null;
    
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Jbp\Customer\Helper\Taxvat $taxvat,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customer
    ) {
        
        $this->_storeManager = $storeManager;
        
        $this->_taxvat = $taxvat;
        
        $this->_messageManager = $messageManager;
        
        $this->_logger = $logger;
        
        $this->_customer = $customer->create();
        
    }
    
    public function execute(EventObserver $observer)
    {
        $taxvat = $observer->getEvent()->getDataObject()->getTaxvat();
        
        $rgStateenrollment = $observer->getEvent()->getDataObject()->getData('rg_stateenrollment');
        
        $email = $observer->getEvent()->getDataObject()->getEmail();
        
        if (!empty($taxvat)) {
            
            $taxvat = $this->_isValidTaxVat($taxvat);
            
            $observer->getEvent()->getDataObject()->setRgStateenrollment(preg_replace('/\D/', '', $rgStateenrollment));
            
            $observer->getEvent()->getDataObject()->setTaxvat($taxvat);
            
            $this->_isTaxvatExists($taxvat, $rgStateenrollment, $email);
            
        }
        
        return $this;
        
    }
    
    protected function _isRequiredSex($typePerson)
    {
        
    }
    
    protected function _isRequiredDob($typePerson)
    {
        
    }
    
    protected function _isValidTaxVat($taxvat)
    {
        $this->_taxvat->setTaxvat($taxvat);
        
        $valida = $this->_taxvat->valida();
        
        if (!$valida) {
            
            $this->_messageManager->addNotice(
                $this->_getMessage('taxvatInvalid',
                    $this->_taxvat->verifica_cpf_cnpj()));
            
            throw new \Exception($this->_getMessage(
                'taxvatInvalid', $this->_taxvat->verifica_cpf_cnpj()));
            
        }
        
        return $this->_taxvat->formata();
        
    }
    
    protected function _isTaxvatExists($taxvat, $rgStateenrollment,  $email)
    {
        $collection = $this->_customer
                           ->addAttributeToFilter(
                               array(
                                   array('attribute' => 'taxvat', 'eq' => $taxvat),
                                   array('attribute' => 'rg_stateenrollment', 'eq' => $rgStateenrollment),
                               )
                           )
                           ->addAttributeToFilter('rg_stateenrollment', array('notnull' => true))
                           ->addAttributeToFilter('email', array('neq' => $email))
                           ->addAttributeToFilter('store_id', array('eq' => $this->_storeManager->getStore()->getId()))
                           ->addAttributeToFilter('website_id', array('eq' => $this->_storeManager->getStore()->getWebsiteId()));
        
                           
        if ($collection->getSize()) {
            
            $this->_messageManager->addNotice(
                $this->_getMessage('taxvatExists',
                    $this->_taxvat->verifica_cpf_cnpj()));
            
            throw new \Exception($this->_getMessage('taxvatExists', $taxvat));
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
    
    protected function _getMessage($type, $taxvat)
    {
        $taxvat= $this->_getTypeDocument($taxvat);
        
        switch ($type) {
            case 'taxvatExists':
                return __(($taxvat == 'CPF' ? $taxvat.'or RG' : $taxvat.'or IE')." is registered click <a href='/customer/account/forgotpassword'>Here</a> for recover password");
            break;
            
            case 'taxvatInvalid':
                return __("Error {$taxvat} invalid");
            break;
        }
        
        
    }
    
}
