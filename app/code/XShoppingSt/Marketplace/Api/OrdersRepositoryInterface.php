<?php
namespace XShoppingSt\Marketplace\Api;

/**
 * Orders CRUD interface.
 */
interface OrdersRepositoryInterface
{
    /**
     * Retrieve seller order by id.
     *
     * @api
     * @param string $id
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve all seller order by seller id.
     *
     * @api
     * @param int $sellerId
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBySellerId($sellerId);

    /**
     * Retrieve order by order id.
     *
     * @api
     * @param int orderId
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByOrderId($orderId);

    /**
     * Retrieve all seller order.
     *
     * @api
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     */
    public function getList();
}
