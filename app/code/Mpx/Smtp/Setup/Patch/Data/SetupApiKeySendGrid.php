<?php

namespace Mpx\Smtp\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class SetupApiKeySendGrid implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    public $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $data = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'smtp/configuration_option/password',
            'value' => getenv('SEND_GRID_API_KEY'),
        ];
        $this->moduleDataSetup->getConnection()->insertOnDuplicate(
            $this->moduleDataSetup->getTable('core_config_data'),
            $data,
            ['value']
        );
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
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
