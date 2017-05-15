<?php

namespace Jbp\Customer\Model\Eav\Entity\Attribute\Source;

class TypePerson extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            
            $this->_options = array(
                
                array(
                    "label" => __("Legal Person"),
                    "value" =>  1
                ),
                
                array(
                    "label" => __("Natural Person"),
                    "value" =>  2
                )
                
            );
            
        }
        return $this->_options;
    }
}