<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Catalog
 * @author    Mpx
 */

namespace Mpx\Catalog\Setup\Patch\Data;

use Magento\Catalog\Helper\DefaultCategoryFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class UpgradeDefaultCategoriesData patch.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpgradeDefaultCategoriesData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @var DefaultCategoryFactory
     */
    private $defaultCategoryFactory;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     * @param DefaultCategoryFactory $defaultCategoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory,
        \Magento\Catalog\Helper\DefaultCategoryFactory $defaultCategoryFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->defaultCategoryFactory = $defaultCategoryFactory;
    }

    /**
     * Set Name Default Category
     */
    public function apply()
    {
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $defaultCategory = $this->defaultCategoryFactory->create();
        $defaultCategoryId = $defaultCategory->getId();

        // Create Default Catalog Node
        $category = $categorySetup->createCategory();
        $category->load($defaultCategoryId)
            ->setStoreId(0)
            ->setName('カテゴリルート')
            ->save();
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
