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
    
    protected $_scopeConfig = null;
    
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Jbp\Customer\Helper\Taxvat $taxvat,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customer
    ) {
        
        $this->_messageManager = $messageManager;
        
        $this->_customer = $customer->create();
        
        $this->_storeManager = $storeManager;
        
        $this->_scopeConfig = $scopeConfig;
        
        $this->_taxvat = $taxvat;
        
        $this->_logger = $logger;

    }
    
    public function execute(EventObserver $observer)
    {
        
        $isEnabled = (int)$this->_scopeConfig->getValue('jbp_configuration/general/active');
        
        if (!$isEnabled) {
            return $this;
        }
        
        $taxvat = $observer->getEvent()->getDataObject()->getTaxvat();
        
        $gender = $observer->getEvent()->getDataObject()->getGender();
        
        $dob = $observer->getEvent()->getDataObject()->getDob();
        
        $typeperson = $observer->getEvent()->getDataObject()->getTypeperson();
        
        $rgStateenrollment = $observer->getEvent()->getDataObject()->getData('rg_stateenrollment');
        
        $email = $observer->getEvent()->getDataObject()->getEmail();
        
        if (!empty($taxvat)) {
            
            $taxvat = $this->_isValidTaxVat($taxvat);
            
            $observer->getEvent()->getDataObject()->setRgStateenrollment(preg_replace('/\D/', '', $rgStateenrollment));
            
            $observer->getEvent()->getDataObject()->setTaxvat($taxvat);
            
            $this->_isTaxvatExists($taxvat, $rgStateenrollment, $email);
            
        }
        
        $this->_isRequiredDob($dob, $typeperson);
        
        $this->_isRequiredGender($gender, $typeperson);
        
        return $this;
        
    }
    
    /**
     * retorn as configurações
     * @param unknown $path
     * @return mixed
     */
    protected function _getConfig($path)
    {
        $config = $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $config;
    }
    
    /**
     * retorna se a genero e requerido
     * @param unknown $gender
     * @param unknown $typeperson
     * @throws \Exception
     * @return boolean
     */
    protected function _isRequiredGender($gender, $typeperson)
    {
        if (($this->_getConfig('jbp_configuration/general/gender_show') == 'req' 
                && in_array($gender, array(1,2,3)) 
            ) || $this->_getConfig('jbp_configuration/general/gender_show') == 'opt'
              || $typeperson == 1) {
            return true;
        }
        
        
        $this->_messageManager->addNotice(
            $this->_getMessage(
                'genderRequired'));
        
        throw new \Exception($this->_getMessage(
            'genderRequired'));
        
    }
    
    /**
     * retorna se a data de nascimento é requerido
     * @param unknown $typePerson
     */
    protected function _isRequiredDob($dob, $typeperson)
    {
        if (($this->_getConfig('jbp_configuration/general/dob_show') == 'req'
            && !empty($dob)) 
              || $this->_getConfig('jbp_configuration/general/gender_show') == 'opt'
              || $typeperson == 1) {
                return true;
        }
        
        $this->_messageManager->addNotice(
            $this->_getMessage(
                'dobRequired'));
        
        throw new \Exception($this->_getMessage(
            'dobRequired'));
    }
    
    /**
     * verifica se o taxvat é válido
     * @param unknown $taxvat
     * @throws \Exception
     * @return string
     */
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
    
    /**
     * verifica se usuário j[a existe através do taxvat
     * @param unknown $taxvat
     * @param unknown $rgStateenrollment
     * @param unknown $email
     * @throws \Exception
     * @return \Jbp\Customer\Observer\BeforeCustomer
     */
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
    
    /**
     * retorna label
     * @param unknown $taxvat
     * @return \Magento\Framework\Phrase|unknown
     */
    protected function _getTypeDocument($taxvat)
    {
        if ($taxvat === false) {
            return __('Taxvat');            
        }
        
        return $taxvat;
        
    }
    
    /**
     * retorna a messagem caso usuário já exista
     * @param unknown $type
     * @param unknown $taxvat
     * @return \Magento\Framework\Phrase
     */
    protected function _getMessage($type = null, $taxvat = null)
    {
        
        if ($taxvat) {
            $taxvat= $this->_getTypeDocument($taxvat);
        }
        
        switch ($type) {
            case 'taxvatExists':
                return __(($taxvat == 'CPF' ? $taxvat.'or RG' : $taxvat.'or IE')." is registered click <a href='/customer/account/forgotpassword'>Here</a> for recover password");
            break;
            
            case 'taxvatInvalid':
                return __("Error {$taxvat} invalid");
            break;
            
            case 'genderRequired':
                return __("Gender Required");
            break;
            
            case 'dobRequired':
                return __("Dob Required");
            break;
        }
        
        
    }
    
}
