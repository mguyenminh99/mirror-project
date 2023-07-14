<?php
namespace XShoppingSt\MarketplaceBaseShipping\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use XShoppingSt\Marketplace\Model\ControllersRepository;

class InsertControllerPath implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var ControllersRepository
     */
    private $controllersRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ControllersRepository $controllersRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ControllersRepository $controllersRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->controllersRepository = $controllersRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $setup->startSetup();

        /**
         * insert sellerstorepickup controller's data
         */
        $data = [];

        if (!count($this->controllersRepository->getByPath('baseshipping/shipping'))) {
            $data[] = [
                'module_name' => 'XShoppingSt_MarketplaceBaseShipping',
                'controller_path' => 'baseshipping/shipping',
                'label' => 'Shipping Setting',
                'is_child' => '0',
                'parent_id' => '0',
            ];

            $setup->getConnection()
                ->insertMultiple($setup->getTable('marketplace_controller_list'), $data);
        }

        $setup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
