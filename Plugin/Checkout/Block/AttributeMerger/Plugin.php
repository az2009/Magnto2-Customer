<?php

namespace Jbp\Customer\Plugin\Checkout\Block\AttributeMerger;

class Plugin
{
  public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result)
  {
        $validation = [0,1,3];
      
        if (array_key_exists('street', $result)) {
          
              $result['street']['children'][1]['label'] = __('Number');
          
              $result['street']['children'][2]['label'] = __('Complement');
          
              $result['street']['children'][3]['label'] = __('Neighborhood');
          
          
              for ($x = 0; $x <= 3; $x++) {
                  $result['street']['children'][$x]['additionalClasses'] = 1;
                  if (in_array($x, $validation) !== false) {
                      $result['street']['children'][$x]['additionalClasses'] = "street true street_{$x} ";
                      $result['street']['children'][$x]['validation'] = 
                          $result['street']['children'][0]['validation'];                  
                  }                  
              }          
        }
    
        return $result;
  }
  
}