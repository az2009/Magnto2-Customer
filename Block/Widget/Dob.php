<?php

namespace Jbp\Customer\Block\Widget;

use \Magento\Customer\Block\Widget\Dob as WidgetDob;

class Dob extends WidgetDob
{
    
    public function _construct()
    {
        parent::_construct();
        
        $this->setTemplate('Jbp_Customer::widget/dob.phtml');
        
    }
    
    /**
     * Create correct date field
     *
     * @return string
     */
    public function getFieldHtml()
    {
        $this->dateElement->setData([
            'extra_params' => $this->isRequired() ? 'data-validate="{required:true}"' . ' placeholder="'.$this->getLabel().'" ': '' . ' placeholder="'.$this->getLabel().'" ',
            'name' => $this->getHtmlId(),
            'id' => $this->getHtmlId(),
            'class' => $this->getHtmlClass(),
            'value' => $this->getValue(),
            'date_format' => $this->getDateFormat(),
            'image' => $this->getViewFileUrl('Magento_Theme::calendar.png'),
            'years_range' => '-120y:c+nn',
            'max_date' => '-1d',
            'change_month' => 'true',
            'change_year' => 'true',
            'placeholder' => 'vdf',
            'show_on' => 'both'
        ]);
        return $this->dateElement->getHtml();
    }
    
    
}