<?php

namespace Jbp\Customer\Controller\Taxvat;

use Jbp\Customer\Controller\Core\Action;
use Magento\TestFramework\Event\Magento;

class Index extends Action
{

    protected $_taxvat;
    
    protected $_customerModel;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Jbp\Customer\Model\Customer\Customer $customerModel
    ) {
        
        $this->_customerModel = $customerModel;
        
        parent::__construct($context,$resultJsonFactory);
        
    }
    
    public function execute()
    {
        
        $result = [];
        
        $code = 200 ;
        
        $taxvat = $this->getRequest()->getParam('taxvat');
        
        if (empty($taxvat)) {
            
            $result['error'] = __('Please enter a CPF/CNPJ valid');
            
            return $this->sendResponse($result, 401);
            
        }
        
        $validate = $this->_customerModel->validateCustomer($taxvat);
        
        if ($validate['result'] == 'taxvat_invalid') {
            
            $code = 401;
            
            $result['error'] = __('Please enter a CPF/CNPJ valid');
            
        } elseif ($validate['result'] == 'customer_exists') {
            
            $code = 403;
            
            $result['error'] = __("Registered CPF / CNPJ login or recover your password if you do not remember, 
                                    click <a href='#'>Here</a> for recore your pass");
            
        } elseif ($validate['result'] == 'success') {
            
            $result['success'] = true;
            
        } else {
            
            $code = 500;
            
            $result['error'] = __('An error has occurred');
            
        }
        
        return $this->sendResponse($result, $code);
        
    }
    
}