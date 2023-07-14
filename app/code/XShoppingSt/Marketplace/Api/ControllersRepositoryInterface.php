<?php
namespace XShoppingSt\Marketplace\Api;

/**
 * Controllers CRUD interface.
 */
interface ControllersRepositoryInterface
{
    /**
     * Retrieve controller by id.
     *
     * @api
     * @param string $controllersId
     * @return \XShoppingSt\Marketplace\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($controllersId);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @param int $moduleName
     * @return \XShoppingSt\Marketplace\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByModuleName($moduleName);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @param int $controllerPath
     * @return \XShoppingSt\Marketplace\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByPath($controllerPath);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @return \XShoppingSt\Marketplace\Api\Data\ControllersInterface
     */
    public function getList();
}
