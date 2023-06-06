<?php

namespace Mpx\ConfigSetting\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Authorization\Model\UserContextInterface;
use Magento\User\Model\UserFactory;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;

class XsAdminAuthorizationRule implements DataPatchInterface
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * RoleFactory
     *
     * @var roleFactory
     */
    private $roleFactory;

    /**
     * RulesFactory
     *
     * @var rulesFactory
     */
    private $rulesFactory;

    /**
     * Init
     */
    public function __construct(
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Authorization\Model\RulesFactory $rulesFactory
    ) {
        $this->userFactory = $userFactory;
        $this->roleFactory = $roleFactory;
        $this->rulesFactory = $rulesFactory;
    }

    /**
     * @return XsAdminAuthorizationRule|void
     * @throws \Exception
     */
    public function apply() {
        $user = $this->userFactory->create()->load("xs-admin", "username");
        $role = $this->roleFactory->create();

        $role->setName('xs-admin')
             ->setPid(0)
             ->setRoleType(RoleGroup::ROLE_TYPE)
             ->setUserType(UserContextInterface::USER_TYPE_ADMIN);

        $role->save();

        $resource=[
            'Magento_Backend::dashboard',
            'Webkul_Marketplace::marketplace',
            'Webkul_Marketplace::menu',
            'Webkul_Marketplace::seller',
            'Webkul_Mpshipping::menu',
            'Webkul_Mpshipping::mpshipping',
            'Webkul_Mpshipping::mpshippingset',
            'Webkul_MpTimeDelivery::menu',
            'Webkul_MpTimeDelivery::slots',
            'Webkul_MpTimeDelivery::order',
            'Magento_Sales::sales',
            'Magento_Sales::sales_operation',
            'Magento_Sales::sales_order',
            'Magento_Sales::actions',
            'Magento_Sales::actions_view',
            'Magento_Sales::shipment',
            'Magento_Catalog::catalog',
            'Magento_Catalog::catalog_inventory',
            'Magento_Catalog::products',
            'Magento_Catalog::categories',
            'Magento_Catalog::edit_category_design',
            'Magento_Customer::customer',
            'Magento_Customer::manage',
            'Magento_Customer::online',
            'Magento_Customer::group',
            'Magefan_LoginAsCustomer::login',
            'Magefan_LoginAsCustomer::login_button',
            'Magefan_LoginAsCustomer::login_log',
            'Magento_Backend::myaccount',
            'Magento_Backend::content',
            'Magento_Backend::content_elements',
            'Magento_Cms::page',
            'Magento_Cms::save',
            'Magento_Cms::save_design',
            'Magento_Cms::page_delete',
            'Magento_Cms::block',
            'Magento_Widget::widget_instance',
            'Magento_Reports::report'
        ];

        $this->rulesFactory->create()
            ->setRoleId($role->getId())
            ->setResources($resource)
            ->saveRel();

        $user->setRoleId($role->getRoleId());
        $user->save();
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
