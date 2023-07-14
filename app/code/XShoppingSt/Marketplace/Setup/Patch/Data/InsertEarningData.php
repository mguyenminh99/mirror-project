<?php
namespace XShoppingSt\Marketplace\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use XShoppingSt\Marketplace\Model\ControllersRepository;

class InsertEarningData implements DataPatchInterface
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
        $data = [];
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        if (!count($this->controllersRepository->getByPath('marketplace/account/earning'))) {
            $data[] = [
                'module_name' => 'XShoppingSt_Marketplace',
                'controller_path' => 'marketplace/account/earning',
                'label' => 'Earnings',
                'is_child' => '0',
                'parent_id' => '0',
            ];
        }
        if (!empty($data)) {
            $connection->insertMultiple($this->moduleDataSetup->getTable('marketplace_controller_list'), $data);
        }
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
