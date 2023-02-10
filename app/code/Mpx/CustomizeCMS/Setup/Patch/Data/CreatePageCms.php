<?php

namespace Mpx\CustomizeCMS\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class CreatePageCms implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var \Mpx\CustomizeCMS\Model\Page
     */
    private $page;

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    private $pageFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Mpx\CustomizeCMS\Model\Page $page
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Cms\Model\PageFactory                    $pageFactory,
        \Mpx\CustomizeCMS\Model\Page                      $page
    ) {
        $this->pageFactory = $pageFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->page = $page;
    }

    /**
     * Create Page Cms
     *
     * @return CreatePrivacyPolicyPages|void
     * @throws \Exception
     */
    public function apply()
    {
        $this->page->install(
            [
                'Mpx_CustomizeCMS::Data/Config/PrivacyPolicyPageConfig.json',
                'Mpx_CustomizeCMS::Data/Config/TermsOfServicePageConfig.json',
                'Mpx_CustomizeCMS::Data/Config/VendorTermsOfUsePageConfig.json'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    private function createPage()
    {
        return $this->pageFactory->create();
    }
}
