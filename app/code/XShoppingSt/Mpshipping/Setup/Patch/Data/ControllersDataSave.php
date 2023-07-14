<?php
namespace XShoppingSt\Mpshipping\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use XShoppingSt\Marketplace\Model\ControllersRepository;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes
 */
class ControllersDataSave implements
    DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
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
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        /**
         * insert Table Rate Shipping controller's data
         */
        $data = [];
        // setup default
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        if (!count($this->controllersRepository->getByPath('mpshipping/shipping/view'))) {
            $data[] = [
               'module_name' => 'XShoppingSt_Mpshipping',
               'controller_path' => 'mpshipping/shipping/view',
               'label' => 'Manage Shipping',
               'is_child' => '0',
               'parent_id' => '0',
            ];
        }

        if (!count($this->controllersRepository->getByPath('mpshipping/shippingset/view'))) {
            $data[] = [
               'module_name' => 'XShoppingSt_Mpshipping',
               'controller_path' => 'mpshipping/shippingset/view',
               'label' => 'Manage Shipping Super Set',
               'is_child' => '0',
               'parent_id' => '0',
            ];
        }

        $connection->insertMultiple($this->moduleDataSetup->getTable('marketplace_controller_list'), $data);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
