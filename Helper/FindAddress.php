<?php

namespace Jbp\Customer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class FindAddress extends AbstractHelper
{
    
    protected $_region;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Directory\Model\RegionFactory $region
    ) {
        
        $this->_region = $region->create();
        
        parent::__construct($context);
        
    }
    
    protected $_url = 'http://m.correios.com.br/movel/buscaCepConfirma.do';
    
    public function getUrl()
    {
        return $this->_url;
    }
 
    public function formatAddress($body)
    {
        
        if( empty($body) ) {
            return false;
        }
        
        @\phpQuery::newDocumentHTML($body, $charset = 'utf-8');
        
        $ufCity = $this->_prepareStateCity(trim(pq('.caixacampobranco .respostadestaque:eq(2)')->html()));
        
        $city = $ufCity[0];
        
        $uf = $ufCity[1];
        
        $data = array(
            'logradouro'    => trim(pq('.caixacampobranco .respostadestaque:eq(0)')->html()),
            'bairro'        => trim(pq('.caixacampobranco .respostadestaque:eq(1)')->html()),
            'cidade'        => trim($city),
            'uf'            => trim($uf),
            'cep'           => trim(pq('.caixacampobranco .respostadestaque:eq(3)')->html())
        );
        
        $regionId   = $this->_region->loadByCode($data['uf'], 'BR')->getId();
        
        $data['uf'] = $regionId;
        
        if(empty($data['logradouro'])){
            return false;
        }
        
        return $data;
        
    }
    
    protected function _prepareStateCity($ufCity)
    {
        $ufCity = explode('/', $ufCity);
        return $ufCity;
    }
    
}