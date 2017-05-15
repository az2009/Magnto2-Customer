<?php
namespace Jbp\Customer\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    protected $customerSetupFactory;

    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
            $this->customerSetupFactory = $customerSetupFactory;
            
            $this->attributeSetFactory = $attributeSetFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
     
        $attributeSet = $this->attributeSetFactory->create();
        
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        
        $attrs = 
        [
            'typeperson' =>
                [
                    'type' => 'varchar',
                    'label' => __('Type Person'),
                    'input' => 'select',
                    'source' => 'Jbp\Customer\Model\Eav\Entity\Attribute\Source\TypePerson',
                    'required' => true,
                    'visible' => true,
                    'sort_order' => 1000,
                    'position' => 1000,
                    'system' => 0
                ],
            'rg_stateenrollment' =>
                [
                    'type' => 'varchar',
                    'label' => __('RG/State Enrollment'),
                    'input' => 'text',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => true,
                    'sort_order' => 1000,
                    'position' => 1000,
                    'system' => 0
                ],
            'cellphone' =>
            [
                'type' => 'varchar',
                'label' => __('Cell Phone'),
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 1000,
                'position' => 1000,
                'system' => 0
            ]

        ];
        
        foreach ($attrs as $k => $v) {
            
            $customerSetup->removeAttribute(Customer::ENTITY, $k);
            
            $customerSetup->addAttribute(Customer::ENTITY, $k,$v);
            
            $attribute = $customerSetup->getEavConfig()
                                       ->getAttribute(Customer::ENTITY, $k)
                                       ->addData(
                                           [
                                                'attribute_set_id' => $attributeSetId,
                                                'attribute_group_id' => $attributeGroupId,
                                                'used_in_forms' => 
                                                    [
                                                        'adminhtml_customer',
                                                        'customer_account_create',
                                                        'customer_account_edit',
                                                        'checkout_register'
                                                    ],
                                           ]
                                       );
            $attribute->save();
        }
        
    }
  
}