<?php

namespace Jbp\Customer\Model\Config\Source;

class Customer {
    
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'opt', 'label' => __('Optional')],
            ['value' => 'req', 'label' => __('Required')]
        ];
    }
    
}