<?php

declare(strict_types=1);

namespace Web200\Seo\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Web200\Seo\Model\Config\Source\MetaRobots;

/**
 * Class AddMetaRobots
 *
 * @package Web200\Seo\Setup\Patch\Data
 */
class AddMetaRobots implements DataPatchInterface
{
    /**
     * Module dataSetup
     *
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    protected $moduleDataSetup;
    /**
     * EavSetup factory
     *
     * @var EavSetupFactory $eavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * AddMetaRobots constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(): AddMetaRobots
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            'meta_robots',
            [
                'type'         => 'varchar',
                'backend'      => '',
                'frontend'     => '',
                'label'        => 'Meta Robots',
                'input'        => 'select',
                'class'        => '',
                'source'       => MetaRobots::class,
                'global'       => Attribute::SCOPE_STORE,
                'visible'      => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => '',
                'searchable'   => false,
                'filterable'   => false,
                'comparable'   => false,
                'unique'       => false,
                'apply_to'     => '',
                'group'        => 'Search Engine Optimization'
            ]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
