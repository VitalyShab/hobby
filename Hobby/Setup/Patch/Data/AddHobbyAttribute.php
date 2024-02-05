<?php

namespace Vitaly\Hobby\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Vitaly\Hobby\Model\Source\Hobby;

class AddHobbyAttribute implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $setup,
        private readonly AttributeResource        $attributeResource,
        private readonly EavConfig                $eavConfig,
        private readonly EavSetupFactory          $eavSetupFactory
    ) {

    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $this->setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            Customer::ENTITY,
            Hobby::HOBBY_ATTR_CODE,
            [
                'type' => 'varchar',
                'label' => 'Hobby',
                'input' => 'select',
                'source' => Hobby::class,
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 100,
                'position' => 100,
                'system' => 0
            ]
        );

        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, Hobby::HOBBY_ATTR_CODE);
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);

        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'customer_account_edit',
            'customer_account_create'
        ]);

        $this->attributeResource->save($attribute);

        $this->setup->endSetup();
    }
}
