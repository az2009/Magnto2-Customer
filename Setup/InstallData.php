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
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    
    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
            $this->customerSetupFactory = $customerSetupFactory;
            
            $this->attributeSetFactory = $attributeSetFactory;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        
        $customerSetup->addAttribute(Customer::ENTITY, 'typeperson', [
            'type' => 'varchar',
            'label' => __('Type Person'),
            'input' => 'select',
            'source' => 'Jbp\Customer\Model\Eav\Entity\Attribute\Source\TypePerson',
            'required' => true,
            'visible' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0
        ]);
        
        $attribute = $customerSetup->getEavConfig()
                                   ->getAttribute(Customer::ENTITY, 'typeperson')
                                   ->addData([
                                        'attribute_set_id' => $attributeSetId,
                                        'attribute_group_id' => $attributeGroupId,
                                        'used_in_forms' => ['adminhtml_customer'],
                                   ]);        
        $attribute->save();
        
        $customerSetup->addAttribute(Customer::ENTITY, 'rg_stateenrollment', [
            'type' => 'varchar',
            'label' => __('RG/State Enrollment'),
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0
        ]);
        
        $attribute = $customerSetup->getEavConfig()
                                   ->getAttribute(Customer::ENTITY, 'rg_stateenrollment')
                                   ->addData([
                                        'attribute_set_id' => $attributeSetId,
                                        'attribute_group_id' => $attributeGroupId,
                                        'used_in_forms' => ['adminhtml_customer'],
                                   ]);
        $attribute->save();
        
    }
  
}